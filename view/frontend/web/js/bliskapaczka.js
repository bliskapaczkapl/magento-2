
function Bliskapaczka()
{
}

Bliskapaczka.showMap = function(operators, googleMapApiKey, testMode, rateCode, codOnly = false)
{
    var now = new Date().getTime();
    while(new Date().getTime() < now + 5000){ /* do nothing */ } 

    aboutPoint = document.getElementById('bpWidget_aboutPoint_sendit_bliskapaczka_sendit_bliskapaczka');
    // aboutPoint.style.display = 'none';

    bpWidget = document.getElementById('bpWidget_sendit_bliskapaczka_sendit_bliskapaczka');
    if (bpWidget != null) {
        bpWidget.style.display = 'block';

        BPWidget.init(
            bpWidget,
            {
                googleMapApiKey: googleMapApiKey,
                callback: function(data) {

                    posCodeForm = document.getElementById('s_method_sendit_bliskapaczka_sendit_bliskapaczka_posCode');
                    posOperatorForm = document.getElementById('s_method_sendit_bliskapaczka_sendit_bliskapaczka_posOperator');

                    posCodeForm.value = data.code;
                    posOperatorForm.value = data.operator;

                    Bliskapaczka.pointSelected(data, operators, rateCode);
                },
                operators: operators,
                posType: 'DELIVERY',
                testMode: testMode,
                codOnly: codOnly
            }
        );
    }
}

Bliskapaczka.pointSelected = function(data, operators, rateCode)
{
    Bliskapaczka.updatePrice(data.operator, operators);

    bpWidget = document.getElementById('bpWidget_sendit_bliskapaczka_sendit_bliskapaczka');
    bpWidget.style.display = 'none';

    aboutPoint = document.getElementById('bpWidget_aboutPoint_sendit_bliskapaczka_sendit_bliskapaczka');
    aboutPoint.style.display = 'block';

    // posDataBlock = document.getElementById('bpWidget_aboutPoint_posData_' + rateCode);

    // posDataBlock.innerHTML =  data.operator + '</br>'
    //     + ((data.description) ? data.description + '</br>': '')
    //     + data.street + '</br>'
    //     + ((data.postalCode) ? data.postalCode + ' ': '') + data.city
}

Bliskapaczka.updatePrice = function (posOperator, operators) {
    // boxSpan = document.getElementsByClassName('bliskapaczka_price_box')[0];
    // if (boxSpan) {
    //     if (boxSpan.getElementsByClassName('price')) {
    //         priceSpan = boxSpan.getElementsByClassName('price')[0];

    //         for (var i = 0; i < operators.length; i++) {
    //             if (operators[i].operator == posOperator) {
    //                 price = operators[i].price;
    //             }
    //         }

    //         priceSpan.innerHTML = priceSpan.innerHTML.replace(/([\d\.,]{2,})/, price);
    //         // Remove word "From"
    //         boxSpan.innerHTML = '';
    //         boxSpan.appendChild(priceSpan)
    //     }
    // }

}
