
	function RadioCheckIni() {
  document.getElementById("injury").style.display = 'block';
		document.getElementById("MedCirt").style.display = 'block';
		document.getElementById("assessment").style.display = 'block';
		document.getElementById("capable").style.display = 'block';
    	document.getElementById("Fitness").style.display = 'block';
    }
 


function RadioCheckFirP() {
  document.getElementById("injury").style.display = 'block';
		document.getElementById("MedCirt").style.display = 'block';
		document.getElementById("assessment").style.display = 'block';
		document.getElementById("capable").style.display = 'block';
    	document.getElementById("Fitness").style.display = 'block';
    }
	

function RadioCheckProg() {
  document.getElementById("injury").style.display = 'none';
		document.getElementById("MedCirt").style.display = 'block';
		document.getElementById("assessment").style.display = 'block';
		document.getElementById("capable").style.display = 'block';
    	document.getElementById("Fitness").style.display = 'block';
    
    }
	
	function RadioCheckFin() {
  document.getElementById("injury").style.display = 'none';
		document.getElementById("MedCirt").style.display = 'block';
		document.getElementById("assessment").style.display = 'none';
		document.getElementById("capable").style.display = 'none';
    	document.getElementById("Fitness").style.display = 'none';
		document.getElementById("FReview").value = '';
    }
	
	

	
	
	
	

function display(obj,id1,id2, id3, id4) {
txt = obj.options[obj.selectedIndex].value;
document.getElementById(id1).style.display = 'none';
document.getElementById(id2).style.display = 'none';
document.getElementById(id3).style.display = 'none';
document.DR_WCMC.UnfitFrom.value = '';
document.DR_WCMC.UnfitTo.value = '';
document.DR_WCMC.SuitFrom.value = '';
document.DR_WCMC.SuitTo.value = '';
document.DR_WCMC.ModFrom.value = '';


document.getElementById(id4).style.display = 'none';

if ( txt.match(id1) ) {
document.getElementById(id1).style.display = 'block';
document.getElementById("assessment").style.display = 'none';
document.getElementById("capable").style.display = 'none';
document.getElementById("Fitness").style.display = 'none';


document.DR_WCMC.AssReq.value = 'No';
document.DR_WCMC.ExamDate.value = '';
document.DR_WCMC.i1.value = '';
document.DR_WCMC.i2.value = '';
document.DR_WCMC.i3.value = '';
document.DR_WCMC.i4.value = '';
document.DR_WCMC.i5.value = '';
document.DR_WCMC.i6.value = '';
document.DR_WCMC.i7.value = '';
document.DR_WCMC.i9.value = '';
document.DR_WCMC.OTHER_RESTRICTIONS.value = '';
document.DR_WCMC.OTH_TXT.value = '';
document.DR_WCMC.FReview.value = '';
document.getElementById("j1").checked = false;
document.getElementById("j2").checked = false;
document.getElementById("j3").checked = false;
document.getElementById("j4").checked = false;
document.getElementById("j5").checked = false;
document.getElementById("j6").checked = false;
document.getElementById("j7").checked = false;
document.getElementById("j8").checked = false;
document.getElementById("j9").checked = false;
document.getElementById("j10").checked = false;
document.getElementById("j11").checked = false;
document.getElementById("j12").checked = false;
document.getElementById("j13").checked = false;






}
if ( txt.match(id2) ) {
document.getElementById(id2).style.display = 'block';
document.getElementById("assessment").style.display = 'block';
document.getElementById("capable").style.display = 'none';
document.getElementById("Yes").style.display = 'none';

document.getElementById("Fitness").style.display = 'block';
 
document.DR_WCMC.AssReq.value = 'Please select';
document.DR_WCMC.ExamDate.value = '';
document.DR_WCMC.i1.value = '';
document.DR_WCMC.i2.value = '';
document.DR_WCMC.i3.value = '';
document.DR_WCMC.i4.value = '';
document.DR_WCMC.i5.value = '';
document.DR_WCMC.i6.value = '';
document.DR_WCMC.i7.value = '';
document.DR_WCMC.i9.value = '';
document.DR_WCMC.OTHER_RESTRICTIONS.value = '';
document.DR_WCMC.OTH_TXT.value = '';
document.DR_WCMC.FReview.value = '';
document.getElementById("j1").checked = false;
document.getElementById("j2").checked = false;
document.getElementById("j3").checked = false;
document.getElementById("j4").checked = false;
document.getElementById("j5").checked = false;
document.getElementById("j6").checked = false;
document.getElementById("j7").checked = false;
document.getElementById("j8").checked = false;
document.getElementById("j9").checked = false;
document.getElementById("j10").checked = false;
document.getElementById("j11").checked = false;
document.getElementById("j12").checked = false;
document.getElementById("j13").checked = false;




}
if ( txt.match(id3) ) {
document.getElementById(id3).style.display = 'block';
document.getElementById("assessment").style.display = 'block';
document.getElementById("capable").style.display = 'block';
document.DR_WCMC.AssReq.value = 'Please select';
document.getElementById("Yes").style.display = 'none';
document.DR_WCMC.ExamDate.value = '';

}
if ( txt.match(id4) ) {
document.getElementById(id4).style.display = 'block';
document.getElementById("assessment").style.display = 'none';
document.getElementById("capable").style.display = 'block';
document.getElementById("Fitness").style.display = 'none';

document.DR_WCMC.MPlan.value = '';
document.DR_WCMC.TReview.value = '';
document.DR_WCMC.AssReq.value = 'No';
document.DR_WCMC.ExamDate.value = '';
document.DR_WCMC.FReview.value = '';
document.getElementById("consult").checked = true;


}
}

function display2(obj,id1,id2) {
txt = obj.options[obj.selectedIndex].value;
document.getElementById(id1).style.display = 'none';
document.getElementById(id2).style.display = 'none';
document.DR_WCMC.ExamDate.value = '';
document.DR_WCMC.ModFrom.value = '';
if ( txt.match(id1) ) {
document.getElementById(id1).style.display = 'block';
}
if ( txt.match(id2) ) {
document.getElementById(id2).style.display = 'block';
}
}

function uncheckAll(field)
{
	if( field )
	{
		for (i = 0; i < field.length; i++)
			field[i].checked = false ;
	}
}

function display2_1(obj,id1,id2) {
txt = obj.options[obj.selectedIndex].value;
document.getElementById(id1).style.display = 'none';
document.getElementById(id2).style.display = 'none';
uncheckAll(document.DR_WCMC.elements["OTH[]"]);



if ( txt.match(id1) ) {
document.getElementById(id1).style.display = 'block';
}
if ( txt.match(id2) ) {
document.getElementById(id2).style.display = 'block';
}
}

function display3(obj,id1) {
txt = obj.options[obj.selectedIndex].value;
document.getElementById(id1).style.display = 'none';
if ( txt.match(id1) ) {
document.getElementById(id1).style.display = 'block';
}
}



