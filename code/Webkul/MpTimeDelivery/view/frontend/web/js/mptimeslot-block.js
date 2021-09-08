/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpTimeDelivery
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    "jquery/ui"
    ],
    function ($, ko, Component, customerData) {
        'use strict';

        $(".wk-mp_time_slot_tab").on("click", function(){
            $('.mptimeslot-div').slideToggle();
        });
        return Component.extend(
            {
                defaults: {
                    template: 'Webkul_MpTimeDelivery/time-slots-product-view'
                },
                sellerId: window.sellerId,
                totalSellerCount: ko.observable(0),
                sellerCount: ko.observable(0),
                selectedSlots: ko.observableArray([]),
                isEnabled: true,
                initialize: function () {
                    this._super();
                    var self = this;
                    let sections = ['mptimedelivery-data'];
                    customerData.invalidate(sections);
                    customerData.reload(sections, true);
                    var customsection = customerData.get('mptimedelivery-data');
                    this.allowedDays = customsection().allowed_days;
                    this.sellersData = customsection().seller;
                    this.startDate = customsection().start_date;
                    this.slots = ko.observableArray([]);
                    this.sortedSlots = ko.observableArray([]),
                    this.isChecked = ko.observable(false);
                    this.currentDate = this.startDate;
                    this.maxDays = customsection().max_days;
                    $.each(
                        this.sellersData,
                        function (i, v) {
                            if (v.id == self.sellerId)
                            self.slots.push(v);
                        }
                    );
                },
                getSellerSlotData: function () {
                    this.totalSellerCount(this.slots().length);
                    return this.slots;
                },
                getSortedSlots: function (data) {
                    var ordered = {};
                    Object.keys(data).sort().forEach(
                        function (key) {
                            ordered[key] = data[key];
                        }
                    );
                    return ordered;
                },
                getDate: function (sellerId , cday) {
                    var sellerStartDate = this.sellersData[sellerId].seller_start_date;
                    var cDate = new Date(cday);
                    var cDay = cDate.getDay();
                    var returnDate;
                    var check = 0;
                    var customsection = customerData.get('mptimedelivery-data');
                    for (var i = 0; i <= this.maxDays; i++) {
                        var nDate = new Date(sellerStartDate);

                        nDate.setDate(nDate.getDate() + check);
                        var day = nDate.getDate();
                        var month = nDate.getMonth() + 1;
                        if (day < 10) {
                            day = "0" + day;
                        }
                        if (month < 10) {
                            month = "0" + month;
                        }
                        cday = cday.replace(/-/g, "/");
                        var d = new Date(nDate.getFullYear() + "-" + month + "-" + day);
                        var n = d.getDay();
                        let convertedDate =new Date(cday + " " + '3:00:00 AM').toLocaleString("en-US", {timeZone: customsection().timezone});
                        let inMiSec = Date.parse(convertedDate);
                        
                        if (n == cDay) {
                            returnDate = $.datepicker.formatDate(
                                'DD, d MM, yy',
                                new Date(inMiSec)
                            );
                            break;
                        }
                        check++;
                    }
                    return returnDate;
                },
                checkDay: function (day, sellerStart) {
                    if (sellerStart) {
                        var d = new Date(sellerStart);
                    } else {
                        var d = new Date(this.startDate);
                    }
                    var requestedDay = new Date(day);
                    if (requestedDay >= d) {
                        return true;
                    }
                    return false;
                },
                checkTime: function (time, date) {
                    var result = time.split('-');
                    var currentTime = new Date().getTime();
                    var slotTime = new Date(this._convertDate(date + " " + result[0].replace(' ', ''))).getTime();

                    if (currentTime < slotTime) {
                        return true;
                    }
                    return false;
                },
                _convertDate: function (date) {
                    var isiPhone = navigator.userAgent.toLowerCase().indexOf("iphone");
                    var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) {
                        return p.toString() === "[object SafariRemoteNotification]";
                    })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));
                    // # valid js Date and time object format (YYYY-MM-DDTHH:MM:SS)
                    var dateTimeParts = date.split(' ');

                    // # this assumes time format has NO SPACE between time and AM/PM marks.
                    if (dateTimeParts[1].indexOf(' ') == -1 && dateTimeParts[2] === undefined) {
                        var theTime = dateTimeParts[1];

                        // # strip out all except numbers and colon
                        var ampm = theTime.replace(/[0-9:]/g, '');

                        // # strip out all except letters (for AM/PM)
                        var time = theTime.replace(/[[^a-zA-Z]/g, '');
                        if (ampm == 'PM') {
                            time = time.split(':');
                            if (time[0] == 12) {
                                time = parseInt(time[0]) + ':' + time[1] + ':00';
                            } else {
                                time = parseInt(time[0]) + 12 + ':' + time[1] + ':00';
                            }
                        } else { // if AM
                            time = time.split(':');
                            if (time[0] < 10) {
                                time = '0' + time[0] + ':' + time[1] + ':00';
                            } else {
                                time = time[0] + ':' + time[1] + ':00';
                            }
                        }
                    }
                    var value = dateTimeParts[0] + 'T' + time;
                    var date = new Date(dateTimeParts[0] + 'T' + time);
                    if (isSafari || isiPhone) {
                        var dt = dateTimeParts[0].split('-');
                        var dat = dt[1] + '/' + dt[2] + '/' + dt[0] + ' ' + time;
                        var date = new Date(dat);
                    }

                    return date;
                },
                checkIsSlotsAvailable: function () {

                },
                refreshVars: function () {
                    this.currentDate = this.startDate;
                },
                generateClass: function (name) {
                    return name.replace(/\s+/g, '-').toLowerCase();
                },
                isSelected: function (model, seller, data, event) {
                    if ($(event.currentTarget).hasClass('disabled') == false) {
                        var elem = event.currentTarget;
                        $('.' + elem.getAttribute('seller-group')).removeClass('selected');
                        $(event.currentTarget).addClass('selected');
                    }

                },
                selectTimeSlot: function (model, seller, data, event) {
                    $(".selected-slots").remove();
                    var elem = event.target || event.srcElement || event.currentTarget;
                    if (typeof elem !== 'undefined') {
                        $('#' + elem.id + '_time').val(elem.getAttribute('value'));
                        $('#' + elem.id + '_date').val(elem.getAttribute('data-date'));

                        if (model.selectedSlots().length == 0) {
                            model.selectedSlots.push({
                                'id': seller.id,
                                'name': seller.name,
                                'slot_time': elem.getAttribute('value'),
                                'date': elem.getAttribute('data-date'),
                                'slot_id': elem.id
                            });
                            model.sellerCount(model.sellerCount() + 1);
                        } else {
                            let flag=1;
                            $.each(model.selectedSlots(),function (index, value) {
                                if (seller.id == value.id) {
                                    model.selectedSlots()[index].slot_time = elem.getAttribute('value');
                                    model.selectedSlots()[index].date = elem.getAttribute('data-date');
                                    model.selectedSlots()[index].slot_id = elem.id;
                                    flag=0;
                                }
                            });
                            if (flag) {
                                model.selectedSlots.push({
                                    'id': seller.id,
                                    'name': seller.name,
                                    'slot_time': elem.getAttribute('value'),
                                    'date': elem.getAttribute('data-date'),
                                    'slot_id': elem.id
                                });
                                model.sellerCount(model.sellerCount() + 1);
                            }
                        }
                    }
                    customerData.set("selected-slots", model.selectedSlots());
                    model.isChecked(true);
                    $('#co-shipping-method-form').append("<input class='selected-slots' type='hidden' name='seller_data' value='" + JSON.stringify(model.selectedSlots()) + "'/>");
                    return true;
                }
            }
        )
    }
);

