function maxLengthCheckphone(object){
if(object.value.length>object.maxLength)
object.value=object.value.slice(0,object.maxLength)
}
function maxLengthCheck(object){
if(object.value.length>object.maxLength)
object.value=object.value.slice(0,object.maxLength)
}

function checkLength(el) {
  if (el.value.length != 10) {
    alert("length must be atleast 10 characters")
  }

}

function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}