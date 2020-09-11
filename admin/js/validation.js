function evaluationvalid(){
	//alert(evaluationvalid)
	if(document.getElementById('sdate').value==''){
		alert('Please select start date');
		document.getElementById('sdate').focus();
		return false;
	}

	if(document.getElementById('starttime').value==''){
		alert('Please select start time');
		document.getElementById('starttime').focus();
		return false;
	}
	
	if((document.getElementById('endtime').value).trim()==''){
		alert('Please select end time.');
		document.getElementById('endtime').focus();
		return false;
	}
	return true;
	
}
function fillblankvalid(){
	if((document.getElementById('fillblankanswermarks').value).trim()==''){
		alert('Please enter marks');
		document.getElementById('fillblankanswermarks').focus();
		return false;
	}
	if((document.getElementById('fillblankque').value).trim()==''){
		alert('Please enter question');
		document.getElementById('fillblankque').focus();
		return false;
	}
	let noofblanks = $('#noofblanks').val();
	if(noofblanks==0){
		alert('Question is not valid.');
		document.getElementById('fillblankque').focus();
		return false;
	} else{
		for(let i=1;i<=noofblanks;i++){
			if((document.getElementById('fillblankanswer'+i).value).trim()==''){
				alert('Please enter answer '+i );
				document.getElementById('fillblankanswer'+i).focus();
				return false;
			}
		}
	}	
	
	return true;	
}
function fillmatchvalid(){	
	if((document.getElementById('matchmarks').value).trim()==''){
		alert('Please enter marks');
		document.getElementById('matchmarks').focus();
		return false;
	}
	if((document.getElementById('qmatch').value).trim()==''){
		alert('Please enter the match question.');
		document.getElementById('qmatch').focus();
		return false;
	}		
	let noofmatchrows = $('#noofmatchrows').val();
	let mflag=0;
	
	for(let i=1;i<=noofmatchrows;i++){
		mflag=0;
		if((document.getElementById('matchrowq'+i).value).trim()==''){
			alert('Please enter row '+i );
			document.getElementById('matchrowq'+i).focus();
			mflag=1;
			return false;
		}
		if((document.getElementById('matchrowopt'+i).value).trim()==''){
			alert('Please enter option '+i );
			document.getElementById('matchrowopt'+i).focus();
			mflag=1;
			return false;
		}			
	}
	if(mflag==0){
		for(let i=1;i<=noofmatchrows;i++){
			if((document.getElementById('matchrowans'+i).value).trim()==''){
				alert('Please enter match answer '+i );
				document.getElementById('matchrowans'+i).focus();
				return false;
			}	
		}
	}
		
	return true;	
}

function singleobjectivevalid(){
	if((document.getElementById('singlemarks').value).trim()==''){
		alert('Please enter marks');
		document.getElementById('singlemarks').focus();
		return false;
	}	
	if((document.getElementById('singlechoicequestion').value).trim()==''){
		alert('Please enter question');
		document.getElementById('singlechoicequestion').focus();
		return false;
	}
	let noofsinglechoice = $('#noofsinglechoice').val();
	for(let i=1;i<=noofsinglechoice;i++){
		if((document.getElementById('singlechoiceans'+i).value).trim()==''){
			alert('Please enter option '+i );
			document.getElementById('singlechoiceans'+i).focus();
			return false;
		}					
	}
	//var myRadio = document.getElementsByName("singleanswer");
	/*let rflag=0;
	if(rflag==0){
	for (var i=1; i <= noofsinglechoice; i++)  {
        if (document.getElementById('sinans'+i).checked==true	)  { 
			rflag=1; 
			break;     
        } else {
        	alert("Choose one correct radio Button for answer !");
			return false;
    		break;
		}
	}
	}*/
		
	return true;	
}
let rflag=0;
function multipleobjectivevalid(){	
	if((document.getElementById('multiplemarks').value).trim()==''){
		alert('Please enter marks');
		document.getElementById('multiplemarks').focus();
		return false;
	}
	if((document.getElementById('multiplechoicequestion').value).trim()==''){
		alert('Please enter question');
		document.getElementById('multiplechoicequestion').focus();
		return false;
	}
	let noofmultiplechoice = $('#noofmultiplechoice').val();
	for(let i=1;i<=noofmultiplechoice;i++){
		if((document.getElementById('muloption'+i).value).trim()==''){
			alert('Please enter option '+i );
			document.getElementById('muloption'+i).focus();
			return false;
		}					
	}
	for (var i=1; i <= noofmultiplechoice; i++)  {
        if (document.getElementById('ans'+i).checked==true	)  { 
			rflag=1; 
			break;     
        }
	}
	if(rflag==0){
		alert('plese check correct answers')
		return false;
	}	
		
	return true;	
}

function freetextvalid(){
	if((document.getElementById('freetextmarks').value).trim()==''){
		alert('Please enter marks');
		document.getElementById('freetextmarks').focus();
		return false;
	}	
	if((document.getElementById('freetextquestion').value).trim()==''){
		alert('Please enter question');
		document.getElementById('freetextquestion').focus();
		return false;
	}
	if((document.getElementById('txtanswer').value).trim()==''){
		alert('Please enter answer');
		document.getElementById('txtanswer').focus();
		return false;
	}
	
	return true;	
}

function uploadimagevalid(){
	if((document.getElementById('picsmarks').value).trim()==''){
		alert('Please enter marks');
		document.getElementById('picsmarks').focus();
		return false;
	}	
	if((document.getElementById('uoloadimagequestion').value).trim()==''){
		alert('Please enter question');
		document.getElementById('uoloadimagequestion').focus();
		return false;
	}	
	return true;	
}
function popupw(url){
window.open(url,'','_blank', 'location=no,height=570,width=520,scrollbars=yes');
}



