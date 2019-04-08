define([
  'jquery',
  'mage/utils/wrapper',
  'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
  'use strict';

  return function (setShippingInformationAction) {

    return wrapper.wrap(setShippingInformationAction, function (originalAction) {
      var shippingAddress = quote.shippingAddress();
      if (shippingAddress['extension_attributes'] === undefined) {
        shippingAddress['extension_attributes'] = {};
      }

      // you can extract value of extension attribute from any place (in this example I use customAttributes approach)
      shippingAddress['extension_attributes']['pos_code'] = jQuery('[id="s_method_sendit_bliskapaczka_sendit_bliskapaczka_posCode"]').val();
      shippingAddress['extension_attributes']['pos_operator'] = jQuery('[id="s_method_sendit_bliskapaczka_sendit_bliskapaczka_posOperator"]').val();

      // pass execution to original action ('Magento_Checkout/js/action/set-shipping-information')
      return originalAction();
    });
  };
});