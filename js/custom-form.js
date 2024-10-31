/* Скрипт кастомизации выпадающих форм */

function tamingselect(select_form_class, add_form_class)
{
	if(!document.getElementById && !document.createTextNode){ return; }
	
// Класи для посилання та видимого спадного меню
	var ts_selectclass=select_form_class; 	//'my-select-options' клас для ідентифікації вибирає - turnintodropdown
	var ts_listclass='turnintoselect';		// клас для ідентифікації UL
	var ts_boxclass='dropcontainer'; 		// батьківський елемент
	var ts_triggeron='activetrigger'; 		// клас для активного тригерного посилання
	var ts_triggeroff='trigger';			// клас для неактивного тригерного посилання
	var ts_dropdownclosed='dropdownhidden'; // закрите спадне меню
	var ts_dropdownopen='dropdownvisible';	// відкрити спадне меню
/*
	Перетворіть усі вибрані елементи на спадні списки DOM
*/
	var count=0;
	var toreplace=new Array();
//	var sels=document.getElementsByTagName('select');
	
  // Знаходимо всі, або із зазначеним классом стандартні форми, що випадають на сторінці
  if(select_form_class != "") {
  var selector = 'select.'+select_form_class;
  var sels = document.querySelectorAll(selector);
  //var sels = document.getElementsByClassName(select_form_class);
  } else {
  var sels = document.getElementsByTagName('select');
  } 
	
	for(var i=0;i<sels.length;i++){
		if (ts_check(sels[i],ts_selectclass))
		{
			var chapter = sels[i].closest('span');
			ts_addclass(chapter,add_form_class);
			
			var hiddenfield=document.createElement('input');
			hiddenfield.name=sels[i].name;
			hiddenfield.type='hidden'; //
			hiddenfield.id=sels[i].id;
			hiddenfield.value=sels[i].options[0].value;
			sels[i].parentNode.insertBefore(hiddenfield,sels[i])
			var trigger=document.createElement('a');
			ts_addclass(trigger,ts_triggeroff+" "+sels[i].getAttribute('class')); 
			trigger.href='#';

		document.onclick=function(){ //onclick onmouseup
			if (ts_check(trigger,ts_triggeron) && !trigger_on) {
//			alert (trigger.className);
				ts_swapclass(trigger,ts_triggeroff,ts_triggeron)
				ts_swapclass(trigger.parentNode.getElementsByTagName('ul')[0],ts_dropdownclosed,ts_dropdownopen);
			} else {
				trigger_on = 0;
			}
			} 
		trigger.onclick=function(){
				ts_swapclass(this,ts_triggeroff,ts_triggeron)
				ts_swapclass(this.parentNode.getElementsByTagName('ul')[0],ts_dropdownclosed,ts_dropdownopen);
				trigger_on = 1;
				return false;
			} 
			trigger.appendChild(document.createTextNode(sels[i].options[0].text));
			sels[i].parentNode.insertBefore(trigger,sels[i]);
			var replaceUL=document.createElement('ul');
			for(var j=0;j<sels[i].getElementsByTagName('option').length;j++)
			{
				var newli=document.createElement('li');
				var newa=document.createElement('a');
				newli.v=sels[i].getElementsByTagName('option')[j].value;
				newli.elm=hiddenfield;
				newli.istrigger=trigger;
				newa.href='#';
				newa.appendChild(document.createTextNode(
				sels[i].getElementsByTagName('option')[j].text));
				newli.onclick=function(){ 
					this.elm.value=this.v;
					ts_swapclass(this.istrigger,ts_triggeron,ts_triggeroff);
					ts_swapclass(this.parentNode,ts_dropdownopen,ts_dropdownclosed)
					this.istrigger.firstChild.nodeValue=this.firstChild.firstChild.nodeValue;
					return false;
				}
				newli.appendChild(newa);
				replaceUL.appendChild(newli);
			}
			ts_addclass(replaceUL,ts_dropdownclosed);
			var div=document.createElement('div');
			div.appendChild(replaceUL);
			ts_addclass(div,ts_boxclass);
			sels[i].parentNode.insertBefore(div,sels[i])
			toreplace[count]=sels[i];
			count++;
			

		}
	}
	
/* Превратите все UL с классом, определенным выше, в раскрывающуюся навигацию. */	

	var uls=document.getElementsByTagName('ul');
	for(var i=0;i<uls.length;i++)
	{
		if(ts_check(uls[i],ts_listclass))
		{
			var newform=document.createElement('form');
			var newselect=document.createElement('select');
			for(j=0;j<uls[i].getElementsByTagName('a').length;j++)
			{
				var newopt=document.createElement('option');
				newopt.value=uls[i].getElementsByTagName('a')[j].href;	
				newopt.appendChild(document.createTextNode(uls[i].getElementsByTagName('a')[j].innerHTML));	
				newselect.appendChild(newopt);
			}
			newselect.onchange=function()
			{
				window.location=this.options[this.selectedIndex].value;
			}
			newform.appendChild(newselect);
			uls[i].parentNode.insertBefore(newform,uls[i]);
			toreplace[count]=uls[i];
			count++;
		}
	}
	for(i=0;i<count;i++){
		toreplace[i].parentNode.removeChild(toreplace[i]);
	}
	function ts_check(o,c)
	{
	 	return new RegExp('\\b'+c+'\\b').test(o.className);
	}
	function ts_swapclass(o,c1,c2)
	{
		var cn=o.className
		o.className=!ts_check(o,c1)?cn.replace(c2,c1):cn.replace(c1,c2);
	}
	function ts_addclass(o,c)
	{
		if(!ts_check(o,c)){o.className+=o.className==''?c:' '+c;}
	}
}

let select_form_class; 

function replaceSelectWithCustomSelect(select_form_class, add_form_class)
{
window.onload=function()
{
	tamingselect(select_form_class, add_form_class);
	// add more functions if necessary
}
}
