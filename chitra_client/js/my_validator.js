// for local server
var image_path = '/images/'; 
var nominee_image_path = '/images/nominee/'; 
var sign_card_path = '/images/sign_card/'; 

//- for remote server
//var image_path = "/chitramcos/images/"; 
//var nominee_image_path = '/chitramcos/images/nominee/'; 
//var sign_card_path = '/chitramcos/images/sign_card/'; 

//----------------------------------------------------------------
function onlyNumbers(evt)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;

}
//----------------------------------------------------------------

function numonly(root){
    var reet = root.value;    
    var arr1=reet.length;      
    var ruut = reet.charAt(arr1-1);   
        if (reet.length > 0){   
        var regex = /[0-9]|\./;   
            if (!ruut.match(regex)){   
            var reet = reet.slice(0, -1);   
            $(root).val(reet);   
            }   
        }  
 }

//----------------------------------------------------------------
function StringToDate(_date,_format,_delimiter)
{
            var formatLowerCase=_format.toLowerCase();
            var formatItems=formatLowerCase.split(_delimiter);
            var dateItems=_date.split(_delimiter);
            var monthIndex=formatItems.indexOf("mm");
            var dayIndex=formatItems.indexOf("dd");
            var yearIndex=formatItems.indexOf("yyyy");
            var month=parseInt(dateItems[monthIndex]);
            month-=1;
            var formatedDate = new Date(dateItems[yearIndex],month,dateItems[dayIndex]);
            return formatedDate;
}
//----------------------------------------------------------------

