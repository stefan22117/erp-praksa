function formatRSD(rsd) {
    var amount = rsd;
    var count = rsd.length;
    var decimal_position = count-3;
    var first_part = amount.substring(0, decimal_position);
    var second_part = amount.substring(decimal_position+1, count);
    var output = "";
    if (first_part.length>3){
        var amount = new String(first_part);
        amount = amount.split("").reverse();
        for ( var i = 0; i <= amount.length-1; i++ ){
            output = amount[i] + output;
            if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = '.' + output;
        }
        output +=","+second_part;
    }else{
        output = first_part+","+second_part;
    }
    
    return output;
}//~!

function formatRSD5(rsd) {
    var amount = rsd;
    var count = rsd.length;
    var decimal_position = count-5;
    var first_part = amount.substring(0, decimal_position-1);
    var second_part = amount.substring(decimal_position+1, count-2);
    var output = "";
    if (first_part.length>3){
        var amount = new String(first_part);
        amount = amount.split("").reverse();
        for ( var i = 0; i <= amount.length-1; i++ ){
            output = amount[i] + output;
            if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = '.' + output;
        }
        output +=","+second_part;
    }else{
        output = first_part+","+second_part;
    }
    
    return output;
}//~!

function validateDecimal(value)    {
        var RE = /^\d*\.?\d*$/;
        if(RE.test(value)){
           return true;
        }else{
           return false;
        }
    }