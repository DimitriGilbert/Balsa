var IS=function()
{
	var t=this;
	
	t.of=function(str,obj)
	{
		if (obj.constructor.toString().indexOf(str) == -1)
		{
			return false;
		}
		else
		{
			return true; 
		}
	}
	
	t.array=function(obj)
	{
		return t.of("Array",obj);
	}
	
	t.string=function(obj)
	{
		return t.of("String",obj);
	}
	
	t.num_int=function(obj)
	{
		return t.of("Int",obj);
	}
	
	t.num_float=function(obj)
	{
		return t.of("Float",obj);
	}
}

var Is=new IS;

var Docel=function()
{
	var t=this;
	
	/*****************************selection d'elements**********************************/
	
	t.id=function(str)
	{
		return document.getElementById(str);
	}
	
	t.class_name=function(str)
	{
		try
		{
			return document.getElementsByClassName(str);	
		}
		catch(e)
		{
			var elements = document.getElementsByTagName("*");
			var result = [];
			for(z=0;z<elements.length;z++)
			{
				if(elements[z].getAttribute("class") == n)
				{
					result.push(elements[z]);
				}
			}
			return result;
		}
	}
	
	t.nth_child=function(elt,nth)
	{
		if(Is.string(elt))
		{
			elt=t.id(elt);
		}
		var c=elt.childNodes;
		var l=c.length;
		if(l>nth)
		{
			return c[l-1];
		}
		else
		{
			return c[nth];
		}
	}
	
	t.sibling=function(elt)
	{
		if(Is.string(elt))
		{
			elt=t.id(elt);
		}
		
		return elt.parentNode.childNodes;
	}
	
	/***************************************modification d'element*******************************/
	
	t.destroy=function(elts)
	{
		if(Is.array(elts))
		{}
		else
		{
			elts=[elts];
		}
		var x=0;
		var y=elts.length;
		while(x<y)
		{
			if(Is.string(elts[x]))
			{
				elts[x]=t.id(elts[x]);
			}
			
			elts[x].parentNode.removeChild(elts[x]);
				
			x++;
		}

	}
	
	t.empty=function(elts)
	{
		if(Is.array(elts))
		{}
		else
		{
			elts=[elts];
		}
		var x=0;
		var y=elts.lentgh;
		var c="";
		var z=0;
		var w=0;
		while(x<y)
		{
			if(Is.string(elts[x]))
			{
				elts[x]=t.id(elts[x]);
			}
			c=elts[x].childNodes;
			w=c.lentgh;
			while(z<w)
			{
				elts[x].removeChild(c[z]);
				z++;
			}
			z=0;
			x++;
		}
	}
	
}

var docel=new Docel;


var Docmap=function()
{
	var t=this;
	t.d=docel;
	
	t.create=function(str)
	{
		return document.createElement(str);
	}
	
	t.append=function(elt,node)
	{
		return node.appendChild(elt);
	}
	
	t.copy=function(elt,node,childs)
	{
		if(child!=false)
		{
			childs=true;
		}
		var elt2=elt.cloneNode(childs);
		return t.append(elt2,node);
	}
	
	t.move=function(elt,node,childs)
	{
		var elt2=t.copy(elt,node,childs);
		t.d.destroy(elt);
		return elt2;
	}
	
	t.append_before=function(elt,node)
	{
		return node.parentNode.insertBefore(elt,node);
	}
	
	t.copy_before=function(elt,node,childs)
	{
		if(child!=false)
		{
			childs=true;
		}
		var elt2=elt.cloneNode(childs);
		return t.append_before(elt2,node);
	}
	
	t.move_before=function(elt,node,childs)
	{
		var elt2=t.copy_before(elt,node,childs);
		t.d.destroy(elt);
		return elt2;

	}
	
	t.append_after=function(elt,node)
	{
		return node.parentNode.insertBefore(elt,node);
	}
	
	t.copy_after=function(elt,node,childs)
	{
		if(child!=false)
		{
			childs=true;
		}
		var elt2=elt.cloneNode(childs);
		return t.append_after(elt2,node);
	}
	
	t.move_after=function(elt,node,childs)
	{
		var elt2=t.copy_after(elt,node,childs);
		t.d.destroy(elt);
		return elt2;

	}
	
	t.copy_attributes=function(elt,n_elt,specif)
	{
		if(specif!=true)
		{
			specif=false;
		}
		var a=elt.attributes;
		var x=0;
		var y=a.lentgh;
		while(x<y)
		{
			if(specif==true)
			{
				if(a[x].specified==true)
				{
					n_elt.setAttribute(a[x].nodeName,a[x].nodeValue);
				}
			}
			else
			{
				n_elt.setAttribute(a[x].nodeName,a[x].nodeValue);
			}
			x++;
		}
	}
	
	t.to_element=function(elt_type,elt)
	{
		var c=elt.childNodes;
		var x=0;
		var y=c.lentgh;
		var nelt=t.create(elt_type);
		t.copy_attributes(elt,nelt);
		while(x<y)
		{
			t.copy(c[x],nelt);
			x++;
		}
		nelt=t.append(nelt,elt.parentNode);
		t.d.destroy(elt);
		return nelt;		
	}
	
	t.offset=function(elt)
	{
		var curleft = curtop = 0;
		if (elt.offsetParent)
		{
			while (elt = elt.offsetParent)
			{
				curleft += elt.offsetLeft;
				curtop += elt.offsetTop;
			}
		}
		else
		{
			if(elt.x)
			{
				curleft=elt.x;
				curtop=elt.y;
			}
			else
			{
				curleft='unavialable';
				curtop='unavialable';
			}
		}
		return [curleft,curtop];
	}
	
	t.position=function(elt)
	{
		if (elt.style.left=='' || elt.style.left=='undefined' || !elt.style.left)
		{
			var l=t.offset(elt)[0];
		}
		else
		{
			var l=parseInt(elt.style.left);
		}
		if(elt.style.top=='' || elt.style.top=='undefined' || !elt.style.top)
		{
			var top=t.offset(elt)[0];
		}
		else
		{
			var top=parseInt(elt.style.top);
		}
		return [l,top];
	}
	
	t.width=function(elt)
	{
		if (elt.style.width=='' || elt.style.width=='undefined' || !elt.style.width)
		{
			return parseInt(elt.offsetWidth);
		}
		else
		{
			return parseInt(elt.style.width);
		}
	}
	
	t.height=function(elt)
	{
		if (elt.style.height=='' || elt.style.height=='undefined' || !elt.style.height)
		{
			return parseInt(elt.offsetHeight);
		}
		else
		{
			return parseInt(elt.style.height);
		}
	}
}

var docmap=new Docmap;
var Timer=function(id)
{
	var t=this;
	//variable pour decelenchement d'action a interval regulier
	t.to_read=[];
	t.reading=false;
	t.current=0;
	t.out='';
	t.interval='';
	
	//variable pour mesurer un interval
	t.start_time='';
	t.snap_times=[];
	
	
	if(!id || id=='')
	{
		t.id='timer';
	}
	else
	{
		t.id=id;
	}
	
	t.add_read=function(f,interval)
	{
		var a=[];
		a['f']=f;
		a['i']=interval;
		t.to_read.push(a);
		return t.to_read.length;
	}
	
	t.read=function()
	{
		t.reading=true;
		t.to_read[t.current]['f']();
		t.current++;
		if(t.current==t.to_read.length)
		{
			t.reading=false;
			clearTimeout(t.out);
			return false;
		}
		t.out=setTimeout(function(){t.read();},t.to_read[t.current]['i']);
	}
}

var timer=new Timer;
var Animation=function(tm)
{
	var t=this;
	t.animated=[];
	t.return_mode=false;
	t.ie=false;
	if(!tm)
	{
		t.timer=timer;
	}
	else
	{
		t.timer=tm;
	}
	
	
	t.move=function(id,x,y)
	{
		var elt=docel.id(id);
		var cur=docmap.position(elt);
		var _x=parseInt(cur[0])+x;
		var _y=parseInt(cur[1])+y;
//		alert(cur+' '+x+' '+_x+' '+y+' '+_y)
		elt.style.position='relative';
		elt.style.top=_y+'px';
		elt.style.left=_x+'px';
	}
	
	t.opacify=function(id,o)
	{
		var elt=docel.id(id);
		var n_o=elt.style.opacity;
		if(n_o=='')
		{
			n_o=1;
		}
		else
		{
			n_o=parseFloat(n_o);
		}
		n_o*=1000;
		n_o=(n_o+o)/1000;
		elt.style.opacity=n_o;
	}
	
	t.width=function(id,o)
	{
		var elt=docel.id(id);
		var cur=docmap.width(elt);
		var _x=cur+o;
		elt.style.width=_x+'px';
	}
	
	t.translation=function(id,left,top,time,fps)
	{
		if(t.animated[id]==undefined)
		{
			var anim_info=[];
			anim_info['id']=id;
			anim_info['names']=['translation'];		
			anim_info['animation']=[];
			anim_info['animation']['f']=[];
			anim_info['animation']['i']=[];
		}
		else
		{
			t.animated[id]['names'].push('translation');
			var anim_info=t.animated[id];
		}
		
		
		if(!time)
		{
			var time=1;
		}
		if(!fps)
		{
			var fps=25;
		}
		var nb_frame=parseInt(time*fps);
		if((top<nb_frame && top>0) || (top>(-nb_frame) && top<0))
		{			
			if(top>0)
			{
				var top_per_frame=1;
				var plus_top=nb_frame-top;
			}
			else
			{
				var top_per_frame=-1;
				var plus_top=(-nb_frame)+top;
			}			
		}
		else
		{
			var top_per_frame=top/nb_frame;
			var plus_top=top%nb_frame;
		}
		if((left<nb_frame && left>0) || (left>(-nb_frame) && left<0))
		{
			var left_per_frame=1;
			if(left>0)
			{
				var left_per_frame=1;
				var plus_left=nb_frame-left;
			}
			else
			{
				var left_per_frame=-1;
				var plus_left=(-nb_frame)+left;
			}			
		}
		else
		{
			var left_per_frame=left/nb_frame;
			var plus_left=left%nb_frame;
		}
		
		var x=0;
		var time_per_frame=(time*1000)/nb_frame;
		var elt=docel.id(id);
		
		if(anim_info['start_position']==undefined)
		{			
			var cur=docmap.position(elt);
			anim_info['start_position']=cur;
		}
		
		top_per_frame=parseInt(top_per_frame);
		left_per_frame=parseInt(left_per_frame);
//		alert(time_per_frame+'ms x : '+left_per_frame+'px y : '+top_per_frame+'px');
		while(x<nb_frame)
		{	
			if(x+1==nb_frame)
			{
				if(!t.return_mode)
				{
					t.timer.add_read(function(){t.move(id,left_per_frame+plus_left,top_per_frame+plus_top);},time_per_frame);
				}
				anim_info['animation']['f'].push(function(){t.move(id,left_per_frame+plus_left,top_per_frame+plus_top);});
				anim_info['animation']['i'].push(time_per_frame);
				
			}
			else
			{
				if(!t.return_mode)
				{
					t.timer.add_read(function(){t.move(id,left_per_frame,top_per_frame);},time_per_frame);
					
				}
				anim_info['animation']['f'].push(function(){t.move(id,left_per_frame,top_per_frame);});
				anim_info['animation']['i'].push(time_per_frame);
				
			}
			
			x++;
		}		
		t.animated[id]=anim_info;
		if(t.timer.reading)
		{
			return anim_info['animation'];
		}
		else
		{
			t.timer.read();
			return anim_info['animation'];
		}
	}
	
	t.opacification=function(id,o,time,fps,start_o)
	{
		o*=1000;
		if(t.animated[id]==undefined)
		{
			var anim_info=[];
			anim_info['id']=id;
			anim_info['names']=['opacification'];
			anim_info['animation']=[];
			anim_info['animation']['f']=[];
			anim_info['animation']['i']=[];
		}
		else
		{
			t.animated[id]['names'].push('opacification');
			var anim_info=t.animated[id];			
		}		
		
		if(!time)
		{
			var time=1;
		}
		if(!fps)
		{
			var fps=25;
		}
		var nb_frame=parseInt(time*fps);		
		
		var x=0;
		var time_per_frame=(time*1000)/nb_frame;
		var elt=docel.id(id);
		
		if(start_o)
		{
			var cur=start_o*1000;
		}
		else
		{
			var cur=elt.style.opacity;
		}
		
		if(cur=='')
		{
			cur=1000;
		}
		else
		{
			cur*=1000;
		}
		
		if(anim_info['start_opacity']==undefined)
		{
			anim_info['start_opacity']=cur;
		}
		
		var opacity_per_frame=(o-cur)/nb_frame;
		
		while(x<nb_frame)
		{
			if(!t.return_mode)
			{
				t.timer.add_read(function(){t.opacify(id,opacity_per_frame);},time_per_frame);
				
			}
			anim_info['animation']['f'].push(function(){t.opacify(id,opacity_per_frame);});
			anim_info['animation']['i'].push(time_per_frame);
			x++;
		}		
		t.animated[id]=anim_info;
		if(t.timer.reading)
		{
			return anim_info['animation'];
		}
		else
		{
			t.timer.read();
			return anim_info['animation'];
		}
	}
	
	t.widen=function(id,o,time,fps,start_o)
	{
		if(t.animated[id]==undefined)
		{
			var anim_info=[];
			anim_info['id']=id;
			anim_info['names']=['widen'];
			anim_info['animation']=[];
			anim_info['animation']['f']=[];
			anim_info['animation']['i']=[];
		}
		else
		{
			t.animated[id]['names'].push('widen');
			var anim_info=t.animated[id];			
		}		
		
		if(!time)
		{
			var time=1;
		}
		if(!fps)
		{
			var fps=25;
		}
		var nb_frame=parseInt(time*fps);		
		
		var x=0;
		var time_per_frame=(time*1000)/nb_frame;
		var elt=docel.id(id);
		
		if(start_o)
		{
			var cur=start_o;
		}
		else
		{
			var cur=docmap.width(elt);
		}
		
		if(cur=='')
		{
			cur=0;
		}
		
		if(anim_info['start_width']==undefined)
		{
			anim_info['start_width']=cur;
		}
		
		if(o>=cur)
		{
			var width_per_frame=(o-cur)/nb_frame;
		}
		else
		{
			var width_per_frame=-((cur-o)/nb_frame);
		}		
		
		while(x<nb_frame)
		{
			if(!t.return_mode)
			{
				t.timer.add_read(function(){t.width(id,width_per_frame);},time_per_frame);
				
			}
			anim_info['animation']['f'].push(function(){t.width(id,width_per_frame);});
			anim_info['animation']['i'].push(time_per_frame);
			x++;
		}		
		t.animated[id]=anim_info;
		if(t.timer.reading)
		{
			return anim_info['animation'];
		}
		else
		{
			if(!return_mode)
			{
				t.timer.read();
			}
			
			return anim_info['animation'];
		}
	}
	
	t.combine=function(combined)
	{
		var old_r_mode=t.return_mode;
		t.return_mode=true;
		var x=0;
		var y=combined.length;
		var z=0;
		var animz=[];
		var maxi=0;
		var anim_array=[];
		animz['f']=[];
		animz['i']=[];
		while(x<y)
		{
			combined[x]=combined[x]();
			if(combined[x].length>maxi)
			{
				maxi=combined[x].length;
			}
			x++;
		}
		while(z<maxi)
		{
			x=0;
			while(x<y)
			{
				if(combined[x]['f'][y]!=undefined)
				{
					if(!old_r_mode)
					{
						t.timer.add_read(combined[x]['f'][y],combined[x]['i'][y]);
					}					
					animz['f'].push(combined[x]['f'][y]);
					animz['i'].push(combined[x]['i'][y]);
				}
				x++;
			}
			z++;
		}
		if(t.timer.reading)
		{
			t.return_mode=old_r_mode;
			return animz;
		}
		else
		{
			if(!old_r_mode)
			{
//				t.timer.read();
			}
			t.return_mode=old_r_mode;
			return animz;
		}
	}
	
	t.replay=function(start_index)
	{
		if(!t.timer.reading)
		{
			if(!start_index)
			{
				t.timer.current=0;
			}
			else
			{
				t.timer.current=start_index;
			}
			t.timer.read();
		}
	}
}

var animation=new Animation;

var JSI=function()
{
	var t=this;
	
	this.text_node=function(content)
	{
		return document.createTextNode(content);
	}
	/*
	creation d'elements html
	*/
	//creation generique d'elements html
	//type : type de l'element
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'element en innerHTML
	this.element=function(type,att_name,att_value,value)
	{
		var elt=document.createElement(type);
		var x=0;
		var y=att_name.length;
		if(att_name)
		{
			while(x<y)
			{
				if(att_name[x]=='node_text_content')
				{
					elt.appendChild(t.text_node(att_value[x]));
				}
				else
				{
					elt.setAttribute(att_name[x],att_value[x]);
				}				
				x++;
			}
			if(value)
			{
				elt.innerHTML=value;
			}
		}
	
		return elt;
	}
	
	//creation d'un element a
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'element en innerHTML
	//blank : si present ouvrira le lien dans un nouvel onglet
	this.a=function(att_name,att_value,href,value,blank)
	{
		att_name.push('href');
		att_value.push(href);
		if(blank)
		{
			att_name.push('target');
			att_value.push('_blank');
		}
		return t.element('a',att_name,att_value,value);
	}
	
	//creation d'un element div
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'element en innerHTML
	this.div=function(att_name,att_value,value)
	{
		return t.element('div',att_name,att_value,value);
	}
	
	//creation d'un element img
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	this.img=function(src,att_name,att_value)
	{
		att_name.push('src');
		att_value.push(src);
		var value;
		return t.element('img',att_name,att_value,value);
	}
	
	//creation d'un element h1
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'element en innerHTML
	this.h1=function(att_name,att_value,value)
	{
		return t.element('h1',att_name,att_value,value);
	}
	
	//creation d'un element h2
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'element en innerHTML
	this.h2=function(att_name,att_value,value)
	{
		return t.element('h2',att_name,att_value,value);
	}
	
	//creation d'un element h3
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'element en innerHTML
	this.h3=function(att_name,att_value,value)
	{
		return t.element('h3',att_name,att_value,value);
	}
	
	//creation d'un element span
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'element en innerHTML
	this.span=function(att_name,att_value,value)
	{
		return t.element('span',att_name,att_value,value);
	}
	
	//creation d'un element select
	//o_val : tableau de valeur des options
	//o_inner : affichage dans l'option
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indices des valeurs doivent correspondre aux indices des nom pour lesquels elle seront attribuées
	this.select=function(o_val,o_inner,att_name,att_value)
	{
		var sel=t.element('select',att_name,att_value);
		var x=0;
		var y=o_val.length;
	
		while(x<y)
		{
			var opt=t.element('option',['value'],[o_val[x]],o_inner[x]);
			sel.appendChild(opt);
			x++;
		}
	
		return sel;
	}
	
	//creation d'un element input type text
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'input
	this.text=function(att_name,att_value,value)
	{
		att_name.push('type');
		att_value.push('text');
		if(value)
		{
			att_name.push('value');
			att_value.push(value);
		}
		return t.element('input',att_name,att_value);
	}
	
	//creation d'un element input type password
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'input
	this.password=function(att_name,att_value,value)
	{
		att_name.push('type');
		att_value.push('password');
		if(value)
		{
			att_name.push('value');
			att_value.push(value);
		}
		return t.element('input',att_name,att_value);
	}
	
	//creation d'un element input type button
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'input
	this.button=function(att_name,att_value,value)
	{
		att_name.push('type');
		att_value.push('button');
		if(value)
		{
			att_name.push('value');
			att_value.push(value);
		}
		return t.element('input',att_name,att_value);
	}
	
	//creation d'un element label
	//i_id : id de l'element pour lequel on crer le label
	//content : contenu du label
	this.label=function(i_id,content)
	{
		return t.element('label',['for','id','class'],[i_id,i_id+'_label','label_form'],content);
	}	
	
	//creation d'elements input type checkbox avec labels contenu dans une div
	this.checkbox=function(i_id,label_cont,atts_name,atts_value,values,cont_class)
	{		
		if(!cont_class)
		{
			var cont_class="";
		}
		var div=t.div(['class'],[cont_class]);
		div.appendChild(t.label(i_id,label_cont));
		for(x in atts_name)
		{
			var div2=t.div(['class'],[''],values[x]);
			var at_n=atts_name[x];
			var at_v=atts_value[x];
			at_v.push('checkbox');
			at_n.push('type');
			at_v.push(i_id+'_'+values[x]);
			at_n.push('id');
			div2.appendChild(t.element('input',at_n,at_v));
			div.appendChild(div2);
		}
		
		return div;
	}
	
	//creation d'un element input type radio avec son label contenu dans une div
	this.radio=function(i_id,label_cont,atts_name,atts_value,values,cont_class)
	{		
		if(!cont_class)
		{
			var cont_class="";
		}
		var div=t.div(['class'],[cont_class]);
		div.appendChild(t.label(i_id,label_cont));
		for(x in atts_name)
		{
			var div2=t.div(['class'],['radio'],values[x]);
			var at_n=atts_name[x];
			var at_v=atts_value[x];
			at_v.push('radio');
			at_n.push('type');
			at_v.push(i_id+'_'+values[x]);
			at_n.push('id');
			at_v.push(i_id);
			at_n.push('name');
			div2.appendChild(t.element('input',at_n,at_v));
			div.appendChild(div2);
		}
		
		return div;
	}
	
	//creation d'un element textarea
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans le textarea
	this.tArea=function(att_name,att_value,value)
	{
		var tarea=t.element('textarea',att_name,att_value);
		if(value)
		{
			tarea.innerHTML=value;
		}
		return tarea;
	}
	
	//creation d'un element input type text avec son label contenu dans une div 
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'input
	//i_id : id de l'input
	//label_cont : contenu du label
	//cont_class : class de la div contenant le label et l'input
	//eg : texte d'exemple
	this.input4form=function(i_id,label_cont,att_name,att_value,value,cont_class,eg)
	{
		if(!cont_class)
		{
			var cont_class="";
		}
		var div=t.div(['class'],[cont_class]);
		div.appendChild(t.label(i_id,label_cont+' : '));
		att_name.push('id');
		att_value.push(i_id);
		div.appendChild(t.text(att_name,att_value,value));
		if(eg)
		{
			div.appendChild(t.div(['class'],['eg'],'exemple : '+eg));
		}
		return div;
	}
	
	//creation d'un element input type text avec son label contenu dans une div, champ obligatoire
	
	//creation d'un element input type text avec son label contenu dans une div 
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'input
	//i_id : id de l'input
	//label_cont : contenu du label
	//cont_class : class de la div contenant le label et l'input
	//eg : texte d'exemple
	this.vInput4form=function(i_id,label_cont,att_name,att_value,value,cont_class,eg)
	{
		if(!cont_class)
		{
			var cont_class="";
		}
		var div=t.div(['class'],[cont_class]);
		div.appendChild(t.label(i_id,label_cont+'* : '));
		att_name.push('id');
		att_value.push(i_id);
		div.appendChild(t.not_empty(t.text(att_name,att_value,value)));
		if(eg)
		{
			div.appendChild(t.div(['class'],['eg'],'exemple : '+eg));
		}
		return div;
	}
	
	//creation d'un element input type password avec son label contenu dans une div, champ obligatoire
	
	//creation d'un element input type text avec son label contenu dans une div 
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'input
	//i_id : id de l'input
	//label_cont : contenu du label
	//cont_class : class de la div contenant le label et l'input
	this.pass4form=function(i_id,label_cont,att_name,att_value,value,cont_class)
	{
		if(!cont_class)
		{
			var cont_class="";
		}
		var div=t.div(['class'],[cont_class]);
		div.appendChild(t.label(i_id,label_cont+'* : '));
		att_name.push('id');
		att_value.push(i_id);
		div.appendChild(t.password(att_name,att_value,value));
		return div;
	}
	
	//creation d'un element textarea avec son label contenu dans une div
	
	//creation d'un element input type text avec son label contenu dans une div 
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	// /!\ les indice des valeurs doivent correspondre au indice des nom pour lesquels elle seront attribuées
	//value : chaine de caractere entré dans l'input
	//i_id : id de l'input
	//label_cont : contenu du label
	//cont_class : class de la div contenant le label et l'input
	this.tArea4form=function(i_id,label_cont,att_name,att_value,value,cont_class)
	{
		if(!cont_class)
		{
			var cont_class="";
		}
		var div=t.div(['class'],[cont_class]);
		div.appendChild(t.label(i_id,label_cont+' : '));
		att_name.push('id');
		att_value.push(i_id);
		div.appendChild(t.tArea(att_name,att_value,value));
		return div;
	}
	
	//creation d'un element iframe
	
	//creation d'un element input type text avec son label contenu dans une div 
	//att_name : tableau de nom d'attribut a ajouter a l'element
	//att_value : tableau de valeur d'attribut a ajouter a l'element
	//url : url de l'iframe
	this.iframe=function(att_name,att_value,url)
	{
		att_name.push('src');
		att_value.push(url);
		return t.element('iframe',att_name,att_value)
	}
	
	//creation d'un element script
	//src : url du script
	//value : innerHTML de la balise script
	this.script=function(src,value)
	{
		if(src=='')
		{
			return t.element('script',['type'],['text/javascript'],value);
		}
		else
		{
			return t.element('script',['src','type'],[src,'text/javascript'],value);
		}
		
	}	
		
	//creation d'un element div pour clear les floats
	//side : left ou right
	this.clear=function(side)
	{
		if(!side)
		{
			var side='both';
		}
		return t.element('div',['style'],['clear:'+side+';']);
	}
	
	//creation d'un upload en ajax
	//id : id de l'element, id_file pour l'id et le name du input type file
	//url : url d'envoi du fichier
	//content : texte de description de l'upload
	this.upload=function(id,url,content)
	{
		if(!content)
		{
			var content='Fichier : ';
		}
		var form=t.element('form',['id','action','method','enctype','target'],[id+'_form',url,'post','multipart/form-data',id+'_up_target'],content);
		form.appendChild(t.element('input',['type','name','id'],['file',id+'_file',id+'_file']));
		form.appendChild(t.element('input',['type','value'],['submit','Envoyer']));
		
		var div=t.div(['id'],[id]);
		div.appendChild(form);
		div.appendChild(t.iframe(['id','name','style'],[id+'_up_target',id+'_up_target','width:0px;height:0px;border:0px solid #fff;'],'#'));
		return div;
	}
	
	/*
	manipulation d'elements html
	*/
	//insert les noeuds contenu dans le tableau childs dans le noeud node
	//childs : array d'element html
	//node : element html parent
	this.insert=function(childs,node)
	{
		var x=0;
		var y=childs.length;
		while(x<y)
		{
			node.appendChild(childs[x]);
			x++;
		}
		return node;
	}
	
	//manipulation du style display
	//type : type de display a appliquer
	//elts : identifiant des element a manipuler
	this.display=function(type,elts)
	{
		var x=0;
		var y=elts.length;
		while(x<y)
		{
			document.getElementById(elts[x]).style.display=type;
			x++;
		}
	}
	
	//mettre l'element elts en display:block
	this.block=function(elts)
	{
		t.display('block',elts);
	}
	
	//mettre l'element elts en display:none
	this.displayNone=function(elts)
	{
		t.display('none',elts);
	}
	
	//ajoute l'atribut att de valeur val a l'element elts'
	//elts element html a modifier
	//att : nom de l'attribut
	//val : valeur de l'attribut
	this.add2Attribute=function(elts,att,val)
	{
		if(elts.hasAttribute(att))
		{
			val=elts.getAttribute(att)+';'+val;
		}
		
		elts.setAttribute(att,val);
		return elts;
	}
	
	
	/*
	manipulation de input
	*/
	//regarde si l'element elts cest vide ou contient la valeur def
	//input : element html input
	//def : valeur par defaut de l'element
	this.not_empty=function(input,def)
	{
		var fn='if(this.value==""';
		if(!def)
		{
			fn+=')';
		}
		else
		{
			fn+=' || this.value=="'+def+'")';
		}		
		fn+='{this.style.background="#FAA0B6";}else{this.style.background="#FFF";}';
		
		return t.add2Attribute(input,'onblur',fn);
	}
	
	//compare la valeur de deux elements
	//input1 : element html input
	//input2_id : id de l'element a comparer
	this.same_value_as=function(input1,input2_id)
	{
		var fn='if(this.value!=document.getElementById("'+input2_id+'").value){this.style.background="#FAA0B6";}else{this.style.background="#5FFA5A";}';
		
		return t.add2Attribute(input1,'onkeyup',fn);
	}
	
	//test de force de mot de passe
	//str : chaine de caratere a tester
	this.is_strong=function(str)
	{
		var strength=0;
		if(str.length>9)
		{
			strength++;
		}
		if((str.match(/[a-z]/)) && (str.match(/[A-Z]/)))
		{
			strength++;
		}
		if(str.match(/\d+/))
		{
			strength++;
		}
		if(str.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))
		{
			strength++;
		}
		if(str.length>=6)
		{
			strength++;
		}
		else
		{
			strength=0;
		}
		return strength
	}
	
	//mise en valeur de la force du mot de passe
	//pass : element html input 
	this.strong_enough=function(pass)
	{
		var str=t.is_strong(new String(pass.value));
		switch(str)
		{
			case 0:
				pass.style.background="#FAA0B6";
				break;			
			case 1:
				pass.style.background="#FAA0B6";
				break;
			case 2:
				pass.style.background="#FACA5A";
				break;
			case 3:
				pass.style.background="#B2FA5A";
				break;
			case 4:
				pass.style.background="#5FFA5A";
				break;
		}
		return pass;
	}
	
	//ajoute le test de valeur du mot de passe a un input
	//input : element html input
	this.strength_checker=function(input)
	{
		var fn='jsi.strong_enough(this)';
		return t.add2Attribute(input,'onkeyup',fn);
	}
	
	//test si la valeur du champs est un mail
	// input : element html input
	this.is_mail=function(input)
	{
		var str=new String(input.value)
		var e_reg = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
		if(str.search(e_reg)==-1)
		{
			input.style.background="#FAA0B6";
		}
		else
		{
			input.style.background="#5FFA5A";
		}
		return input;
	}
	
	//ajoute le test de mail a un input
	//input : element html input
	this.mail_checker=function(input)
	{
		var fn='jsi.is_mail(this)';
		return t.add2Attribute(input,'onblur',fn);
	}
}













