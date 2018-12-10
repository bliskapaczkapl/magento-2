var config = {
  config: {
    mixins: {
      'Magento_Checkout/js/view/shipping': {
        'Sendit_Bliskapaczka/js/view/shipping': true
      }
    }
  },
  "map": {
    "*": {
      "Magento_Checkout/js/model/shipping-save-processor/default" : "Sendit_Bliskapaczka/js/shipping-save-processor"
    }
  }
};