/*
* Tencent is pleased to support the open source community by making WeUI.js available.
* 
* Copyright (C) 2017 THL A29 Limited, a Tencent company. All rights reserved.
* 
* Licensed under the MIT License (the "License"); you may not use this file except in compliance
* with the License. You may obtain a copy of the License at
* 
*       http://opensource.org/licenses/MIT
* 
* Unless required by applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing permissions and
* limitations under the License.
*/

import $ from '../util/util';
import dialog from '../dialog/dialog';

/**
 * alert 警告弹框，功能类似于浏览器自带的 alert 弹框，用于提醒、警告用户简单扼要的信息，只有一个“确认”按钮，点击“确认”按钮后关闭弹框。
 * @param {string} content 弹窗内容
 * @param {function=} yes 点击确定按钮的回调
 * @param {object=} options 配置项
 * @param {string=} options.title 弹窗的标题
 * @param {string=} options.className 自定义类名
 * @param {array=} options.buttons 按钮配置项，详情参考dialog
 *
 * @example
 * weui.alert('普通的alert');
 * weui.alert('带回调的alert', function(){ console.log('ok') });
 * var alertDom = weui.alert('手动关闭的alert', function(){
 *     return false; // 不关闭弹窗，可用alertDom.hide()来手动关闭
 * });
 * weui.alert('自定义标题的alert', { title: '自定义标题' });
 * weui.alert('带回调的自定义标题的alert', function(){
 *    console.log('ok')
 * }, {
 *    title: '自定义标题'
 * });
 * weui.alert('自定义按钮的alert', {
 *     title: '自定义按钮的alert',
 *     buttons: [{
 *         label: 'OK',
 *         type: 'primary',
 *         onClick: function(){ console.log('ok') }
 *     }]
 * });
 *
 * // 多次使用
 * var alert = weui.alert('hello');
 * alert.hide(function(){
 *     weui.alert('world');
 * });
 */
function alert(content = '', yes = $.noop, options) {
    if (typeof yes === 'object') {
        options = yes;
        yes = $.noop;
    }

    options = $.extend({
        content: content,
        buttons: [{
            label: '确定',
            type: 'primary',
            onClick: yes
        }]
    }, options);

    return dialog(options);
}
export default alert;
