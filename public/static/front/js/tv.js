var tvBlackBgColor = '#1f2126' // 黑色背景色
var tvIntervalList = ['1', '5', '15', '30', '60', '240', 'D', 'W'] // 所有周期
var tvIntradayMultipliers = ['1', '5', '15', '30', '60', '240'] // 日内周期

var tvCurrentData = {};

/**
 * 一个对象，往上面挂[id]: callback；
 * id为键，对应一个请求历史数据的请求，
 * callback是该请求历史数据返回的回调函数，
 * 且回调函数会延时清理自身过期id callback
 * {
 *   1： function(){}
 *   2： function(){}
 * }
 */
var tvHistoryKeyId = 1 // 自增id,本地维护
var tvHistoryCallbackMap = {}

var tvSocket = null // websocket 实例
var tvWidget = null // tv实例
var tvSubscribeId = 10000000 // 自增id, 本地维护
var realtimeCallback = null // tv实时更新回调函数

// 获取当前交易对 如BTC/USDT
function getSymbol() {
  return tvCurrentData.coinSymbol + '/' + tvCurrentData.baseSymbol
}

// 获取请求数据时的交易对 如BTCUSDT
function requestSymbol() {
  return tvCurrentData.coinSymbol + tvCurrentData.baseSymbol
}


// 不同的周期，默认取得的历史数据量不一样
function getTimeframe() {
  var map = {
    '1': '1D',
    '5': '1D',
    '15': '1D',
    '30': '2D',
    '60': '3D',
    '240': '5D',
    'D': '1M',
    'W': '2M',
    'M': '3M'
  }
  return map[tvCurrentData.interval]
}

// 存储均线id
var movingAverage = {
  'id_5': null,
  'id_10': null,
  'id_30': null,
  'id_60': null
}

// 创建指标，四条平均线
function createMovingAverage() {
  var b = tvCurrentData.coinScale // 如 BTC 7890.22 表示小数位只要2位
  movingAverage.id_5 = tvWidget.chart().createStudy('Moving Average', false, false, [5, 'close', 0], null, {
    'Plot.color': 'rgb(150, 95, 196)',
    'precision': b
  })
  movingAverage.id_10 = tvWidget.chart().createStudy('Moving Average', false, false, [10, 'close', 0], null, {
    'Plot.color': 'rgb(116,149,187)',
    'precision': b
  })
  movingAverage.id_30 = tvWidget.chart().createStudy('Moving Average', false, false, [30, 'close', 0], null, {
    'plot.color': 'rgb(58,113,74)',
    'precision': b
  })
  movingAverage.id_60 = tvWidget.chart().createStudy('Moving Average', false, false, [60, 'close', 0], null, {
    'plot.color': 'rgb(118,32,99)',
    'precision': b
  })
}

// 显示平均线
function showMovingAverage() {
  var arr = ['5', '10', '30', '60']
  for (var i = 0; i < arr.length; i++) {
    try {
      // 某个指标有可能被删除
      var t = tvWidget.chart().getStudyById(movingAverage['id_' + arr[i]])
      !t.isVisible() && t.setVisible(true)
    } catch (e) {
      // console.error(e)
    }
  }
}

// 隐藏平均线
function hideMovingAverage() {
  var arr = ['5', '10', '30', '60']
  for (var i = 0; i < arr.length; i++) {
    try {
      // 某个指标有可能被删除
      var t = tvWidget.chart().getStudyById(movingAverage['id_' + arr[i]])
      t.isVisible() && t.setVisible(false)
    } catch (e) {
      // console.error(e)
    }
  }
}

// 覆盖四条均线指标的小数位,切换币种时用
function movingAverageApplyOverrides() {
  var b = tvCurrentData.coinScale // 如 BTC 7890.22 表示小数位只要2位
  var arr = ['5', '10', '30', '60']
  for (var i = 0; i < arr.length; i++) {
    try {
      // 某个指标有可能被删除
      tvWidget.chart().getStudyById(movingAverage['id_' + arr[i]]).applyOverrides({
        'precision': b
      })
    } catch (e) {
      // console.error(e)
    }
  }
}

// 周期转换为秒
function getSecondsByInterval(interval) {
  // 周期 ['1', '5', '15', '30', '60', '240', 'D', 'W']
  var map = {
    '1': 60,
    '5': 60 * 5,
    '15': 60 * 15,
    '30': 60 * 30,
    '60': 60 * 60,
    '240': 60 * 240,
    'D': 60 * 60 * 24,
    'W': 60 * 60 * 24 * 7
  }
  return map[interval + '']
}

// 创建数据源
function datafeedCreate() {
  return {
    onReady: function (cb) {
      var config = {
        supported_resolutions: tvIntervalList
      }
      setTimeout(function () {
        cb(config)
      })
    },
    resolveSymbol: function (symbolName, onSymbolResolvedCallback, onResolveErrorCallback) {
      var symbolStub = {
        name: symbolName,
        type: 'bitcoin',
        session: '24x7',
        timezone: 'Asia/Shanghai',
        ticker: symbolName,
        minmov: 1,
        pricescale: Math.pow(10, tvCurrentData.baseScale), // 市场精度是可变的
        has_intraday: true,
        has_daily: true,
        has_weekly_and_monthly: true,
        intraday_multipliers: tvIntradayMultipliers,
        supported_resolution: tvIntervalList,
        volume_precision: tvCurrentData.volumeScale, // 成交量精度
        data_status: 'streaming'
      }
      setTimeout(function () {
        onSymbolResolvedCallback(symbolStub)
      }, 0)
    },


    // 这里请求历史数据，tv实例化,切换周期，切换币种都会走这里的流程
    getBars: function (symbolInfo, resolution, from, to, onHistoryCallback, onErrorCallback, firstDataRequest) {
      var seconds
      switch (resolution) {
        case 'D':
        case '1D':
          seconds = getSecondsByInterval('D') // 转换为秒
          break

        case 'W':
        case '1W':
          seconds = getSecondsByInterval('W')
          break

        case 'M':
        case '1M':
          seconds = getSecondsByInterval('M')
          break

        default:
          seconds = getSecondsByInterval(resolution)
          break
      }

      // 获取历史数据
      var id = tvHistoryKeyId++
      var key = id + '' // 转换为string类型
      tvHistoryCallbackMap[key] = function (data) {
        tvHistoryCallbackMap[key]['t'] && clearTimeout(tvHistoryCallbackMap[key]['t']) // 如果有数据回来就去掉超时处理
        if (data.result && data.result.length) {
          var arr = []
          var interval = tvCurrentData.interval
          var isD_W_M = (interval === 'D') || (interval === 'W') || (interval === 'M') // 周期是DWM就加8小时
          isD_W_M && true
          for (var i = 0; i < data.result.length; i++) {
            var item = data.result[i];
            var time = item[0] * 1000;
            if (isD_W_M) {
              time = (item[0] + 8 * 60 * 60) * 1000
            }
            arr.push({
              time: time,
              open: Number(item[1]),
              high: Number(item[3]),
              low: Number(item[4]),
              close: Number(item[2]),
              volume: Number(item[5])
            })
          }

          onHistoryCallback(arr, {
            noData: false
          })

          if (firstDataRequest) {
            // 第一次请求就设置可见范围
            setKLineVisibleRange(arr.length, arr[arr.length - 1].time)
          }
        } else {
          // 无数据
          onHistoryCallback([], {noData: true})
        }

        // 删掉自身，用过了没有用了，回收内存
        setTimeout(function () {
          delete tvHistoryCallbackMap[key]
        }, 20)
      }

      // 30秒超时处理
      tvHistoryCallbackMap[key]['t'] = setTimeout(function () {
        onHistoryCallback([], {noData: true}) // 无数据
      }, 30 * 1000)

      // 请求历史数据
      var senData = JSON.stringify({
        id: id,
        method: 'kline.query',
        params: [requestSymbol(), from, to, seconds]
      })

      tvSocket.send(senData)
    },

    subscribeBars: function (symbolInfo, resolution, onRealtimeCallback, subscribeUID, onResetCacheNeededCallback) {
      realtimeCallback = onRealtimeCallback // 挂到外面作用域上面去

      var lastData = {
        id: tvSubscribeId,
        method: 'kline.unsubscribe',
        params: []
      }

      tvSocket.send(JSON.stringify(lastData)) // 取消订阅

      tvSubscribeId += 1
      var senData = {
        id: tvSubscribeId,
        method: 'kline.subscribe',
        params: [requestSymbol(), getSecondsByInterval(tvCurrentData.interval)]
      }

      tvSocket.send(JSON.stringify(senData)) // 新订阅
    },
    unsubscribeBars: function (subscriberUID) {
      // 取消订阅
    }
  }
}

// 设置可见K线范围
function setKLineVisibleRange(barsLength, lastBarTime) {
  var count = 100 // 显示几条k线

  var interval = tvCurrentData.interval
  var to = lastBarTime / 1000 // 历史数据的最后一条柱子的时间
  var toNum = 5 // 多显示几条k线距离
  var seconds // 秒数
  switch (interval) {
    case 'D':
      seconds = 24 * 60 * 60
      break
    case 'W':
      seconds = 7 * 24 * 60 * 60
      break
    default:
      seconds = +interval * 60
      break
  }
  var options = {
    from: to - (seconds * count),
    to: to + (seconds * toNum) // 可见的结束时间，因为结束时间可能不是当前时间，为了与右边价格刻度有一点距离所以加toNum条柱子的时间
  }
  tvWidget.chart().setVisibleRange(options)
}

// 初始化tv
function initTradingview() {
  var widgetOptions = {
    debug: false,
    symbol: getSymbol(),
    datafeed: datafeedCreate(),
    interval: tvCurrentData.interval,
    container_id: 'tv-container',
    library_path: '/static/charting_library/',
    custom_css_url: '/static/front/style/tv_override.css', // 自己写的覆盖样式
    locale: tvCurrentData.language,
    charts_storage_url: 'https://saveload.tradingview.com',
    client_id: 'tradingview.com',
    user_id: 'public_user_id',
    fullscreen: false,
    autosize: true,
    toolbar_bg: tvCurrentData.bgColor, // 工具栏背景色
    disabled_features: [
      'header_widget', // 直接禁用掉整条顶部工具条
      'timeframes_toolbar', // 底部时间条
      'volume_force_overlay', // 交易量与k线柱子分离
      'adaptive_logo'
    ],
    enabled_features: [
      'hide_last_na_study_output',
      'move_logo_to_main_pane'
    ],
    overrides: {
      'volumePaneSize': 'medium', // tiny small medium large
      'paneProperties.topMargin': 5,
      'paneProperties.bottomMargin': 5,
      'paneProperties.background': tvCurrentData.bgColor, // k线面板背景
      'mainSeriesProperties.style': tvCurrentData.chartType, // k线图类型
      'mainSeriesProperties.candleStyle.borderDownColor': '#fa5252', // 蜡烛图颜色
      'mainSeriesProperties.candleStyle.borderUpColor': '#12b886', // 蜡烛图颜色
      'mainSeriesProperties.candleStyle.downColor': '#fa5252', // 蜡烛图颜色
      'mainSeriesProperties.candleStyle.upColor': '#12b886', // 蜡烛图颜色
      'mainSeriesProperties.candleStyle.wickDownColor': '#fa5252', // 蜡烛图颜色
      'mainSeriesProperties.candleStyle.wickUpColor': '#12b886', // 蜡烛图颜色
      'paneProperties.horzGridProperties.color': 'rgba(255, 255, 255, 0.06)', // 水平网格线
      'paneProperties.vertGridProperties.color': 'rgba(255, 255, 255, 0.06)', // 竖直网格线
      'paneProperties.legendProperties.showLegend': false, // 展开四条均线
      'paneProperties.legendProperties.showBarChange': true, // 涨跌幅
      'scalesProperties.lineColor': 'rgb(90, 104, 129)', // 价格轴、时间轴颜色
      'scalesProperties.textColor': 'rgb(90, 104, 129)', // 价格、时间颜色
      'mainSeriesProperties.areaStyle.color1': 'rgba(255, 255, 255, 0.2)', // 分时图
      'mainSeriesProperties.areaStyle.color2': 'rgba(255, 255, 255, 0.05)', // 分时图
      'mainSeriesProperties.areaStyle.linecolor': '#fff' // 分时图线
    },
    studies_overrides: {
      'volume.volume.color.0': '#fa5252', // rgba 才行 后面的99才起作用
      'volume.volume.color.1': '#12b886',
      'volume.volume.transparency': 99
    },
    timezone: 'Asia/Shanghai',
    theme: 'Dark',
    timeframe: getTimeframe(),
    has_no_volume: true,
    customFormatters: {
      timeFormatter: {
        format: function (date) {
          function format(a) {
            return a < 10 ? '0' + a : a
          }

          var h = format(date.getUTCHours())
          var m = format(date.getUTCMinutes())
          return `${h}:${m}`
        }
      },
      dateFormatter: {
        format: function (date) {
          function format(a) {
            return a < 10 ? '0' + a : a
          }

          var Y = date.getUTCFullYear()
          var M = format(1 + date.getUTCMonth())
          var D = format(date.getUTCDate())
          return Y + '-' + M + '-' + D
        }
      }
    }
  }
  tvWidget = new TradingView.widget(widgetOptions)
  tvWidget.onChartReady(function () {
    createMovingAverage()
  })
}

// 绑定点击周期事件，切换周期
function addIntervalEventHandle() {
  var intervalWrapper = document.getElementById('intervalWrapper');

  // 事件代理，父节点代理所有子节点事件
  intervalWrapper.addEventListener('click', function (e) {
    var target = e.target
    if (target.classList.contains('interval-item')) {
      // 获取这个元素的周期，调用tv切换周期api setResolution
      var interval = target.getAttribute('data-interval')
      var chartType = target.getAttribute('data-chartType')
      if (tvWidget) {
        if (+chartType !== tvCurrentData.chartType) {
          // 切换图类型
          tvCurrentData.chartType = +chartType // tvCurrentData的数据要维护好
          tvWidget.chart().setChartType(tvCurrentData.chartType)

          // 分时图
          if (tvCurrentData.chartType === 3) {
            // 隐藏MA
            hideMovingAverage()
          } else {
            // 显示ma
            showMovingAverage()
          }
        }
        tvCurrentData.interval = interval // tvCurrentData的数据要维护好
        tvWidget.chart().setResolution(tvCurrentData.interval, function () {
          // 切换周期成功
        })

        // 改变css效果
        for (var i = 0; i < intervalWrapper.childNodes.length; i++) {
          var item = intervalWrapper.childNodes[i];
          if (item === target) {
            item.className = 'interval-item active'
          } else {
            item.className = 'interval-item'
          }
        }
      }
    }
  })
}

// 全屏
function addFullScreenEventHandle() {
  function fullScreen(el) {
    var rfs = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullScreen;

    //typeof rfs != "undefined" && rfs
    if (rfs) {
      rfs.call(el);
    } else if (typeof window.ActiveXObject !== "undefined") {
      //for IE，这里其实就是模拟了按下键盘的F11，使浏览器全屏
      var wscript = new ActiveXObject("WScript.Shell");
      if (wscript != null) {
        wscript.SendKeys("{F11}");
      }
    }
  }

  //退出全屏
  function exitScreen() {
    var el = document;
    var cfs = el.cancelFullScreen || el.webkitCancelFullScreen || el.mozCancelFullScreen || el.exitFullScreen;

    //typeof cfs != "undefined" && cfs
    if (cfs) {
      cfs.call(el);
    } else if (typeof window.ActiveXObject !== "undefined") {
      //for IE，这里和fullScreen相同，模拟按下F11键退出全屏
      var wscript = new ActiveXObject("WScript.Shell");
      if (wscript != null) {
        wscript.SendKeys("{F11}");
      }
    }
  }

  var isFull = false
  document.getElementById('fullscreen').addEventListener('click', function () {
    fullScreen(document.getElementById('tv-container'))
  })
}

// 打开设置弹框
function addTVSettingDialogEventHandle() {
  document.getElementById('tvSetting').addEventListener('click', function () {
    tvWidget.chart().executeActionById('chartProperties')
  })
}

// 打开指标弹框
function addIndicatorDialogEventHandle() {
  document.getElementById('indicator').addEventListener('click', function () {
    tvWidget.chart().executeActionById('insertIndicator')
  })
}


// init函数
function tvInit(cb) {

  tvSocket.addEventListener('error', function (e) {
    // console.error('ws error:', e)
  })

  tvSocket.addEventListener('close', function (e) {
    // console.warn('ws 关闭:', e)
  })

  tvSocket.addEventListener('open', function (e) {
    // console.log('ws连接成功')
    cb && cb() // 连接成功才能干其他事
  })

  tvSocket.addEventListener('message', function (e) {
    var data = JSON.parse(e.data)
    var key = data.id + ''
    if (tvHistoryCallbackMap[key]) {
      tvHistoryCallbackMap[key](data) // 更新历史数据
    } else if (data.method && data.method === 'kline.update') {
      // 如果是实时推送更新id
      if (typeof realtimeCallback === 'function') {
        var symbol = data.params[0][7]
        // 这里最好返回一个周期，光判断交易对是否相等还不够
        if (symbol === requestSymbol()) {
          var item = data.params[0]
          var time = (item[0]) * 1000;
          var interval = tvCurrentData.interval
          var isD_W_M = (interval === 'D') || (interval === 'W') || (interval === 'M') // 周期是DWM就加8小时
          isD_W_M && true
          if (isD_W_M) {
            time = (item[0] + 8 * 60 * 60) * 1000
          }
          var barNew = {
            time: time,
            open: Number(item[1]),
            high: Number(item[3]),
            low: Number(item[4]),
            close: Number(item[2]),
            volume: Number(item[5])
          }

          // console.log('实时更新：', item)
          realtimeCallback(barNew) // 实时更新塞给tv
        }
      }
    }
  })
}

// 先引入这个JS文件，然后在websocket连接成功后，调用这个方法，启动TradingView
/**
 * 启动
 * @param  {Object} _ws     已经连接成功的Websocket对象
 * @param  {Object} _config Tv配置项，计价币种和交易币种必须要传入
 */
function tvStart(_ws, _config){

  tvSocket = _ws;

  // 重要：k线图配置的重要数据，当前k线维护的一份数据，k线切换时，改变周期时都要变对应的值
  tvCurrentData = {

    baseSymbol: _config.baseSymbol, // 计价币种
    coinSymbol: _config.coinSymbol, // 交易币种
    baseScale: _config.baseScale || 2, // 交易币种精度
    coinScale: _config.coinScale || 2, // 计价币种精度
    volumeScale: _config.volumeScale || 2, // 成交量精度保留
    interval: _config.interval || '15', // 15分钟
    chartType: _config.chartType || 1, // 默认蜡烛图：1， 分时图：3 int类型
    bgColor: _config.bgColor || tvBlackBgColor, // 当前选择背景色
    language: _config.language || 'zh'
  }

  // 保证ws初始化连接ws成功才能进行下一步
  tvInit(function () {
    initTradingview()
    addIntervalEventHandle() // 处理切换周期事件
    addFullScreenEventHandle() // 全屏事件
    addTVSettingDialogEventHandle() // 设置弹框设置
    addIndicatorDialogEventHandle() // 指标弹框设置
  });
}