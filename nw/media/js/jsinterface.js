var JSI=function()
{
	var t=this;
	/*
	creation d'elements html
	*/
	this.element=function(type,att_name,att_value,value)
	{
		var elt=document.createElement(type);
		var x=0;
		var y=att_name.length;
		if(att_name)
		{
			while(x<y)
			{
				elt.setAttribute(att_name[x],att_value[x]);
				x++;
			}
			if(value)
			{
				elt.innerHTML=value;
			}
		}
	
		return elt;
	}
	
	this.div=function(att_name,att_value,value)
	{
		return t.element('div',att_name,att_value,value);
	}
	
	this.span=function(att_name,att_value,value)
	{
		return t.element('span',att_name,att_value,value);
	}
	
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
	
	this.clear=function(side)
	{
		if(!side)
		{
			var side='both';
		}
		return t.element('div',['style'],['clear:'+side+';']);
	}
	
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
	
	this.label=function(i_id,content)
	{
		return t.element('label',['for','id'],[i_id,i_id+'_label'],content);
	}
	
	this.input4form=function(i_id,label_cont,att_name,att_value,value)
	{
		var div=t.div(['class'],['']);
		div.appendChild(t.label(i_id,label_cont));
		att_name.push('id');
		att_value.push(i_id);
		div.appendChild(t.text(att_name,att_value,value));
		return div;
	}
	
	this.vInput4form=function(i_id,label_cont,att_name,att_value,value)
	{
		var div=t.div(['class'],['']);
		div.appendChild(t.label(i_id,label_cont));
		att_name.push('id');
		att_value.push(i_id);
		div.appendChild(t.not_empty(t.text(att_name,att_value,value)));
		return div;
	}
	
	this.pass4form=function(i_id,label_cont,att_name,att_value,value)
	{
		var div=t.div(['class'],['']);
		div.appendChild(t.label(i_id,label_cont));
		att_name.push('id');
		att_value.push(i_id);
		div.appendChild(t.password(att_name,att_value,value));
		return div;
	}
	
	this.tArea=function(att_name,att_value,value)
	{
		var tarea=t.element('textarea',att_name,att_value);
		if(value)
		{
			tarea.innerHTML=value;
		}
		return tarea;
	}
	
	this.iframe=function(att_name,att_value,url)
	{
		att_name.push('src');
		att_value.push(url);
		return t.element('iframe',att_name,att_value)
	}
	
	
	
	/*
	manipulation d'elements html
	*/
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
	
	this.block=function(elts)
	{
		t.display('block',elts);
	}
	
	this.displayNone=function(elts)
	{
		t.display('none',elts);
	}
	
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
	
	this.same_value_as=function(input1,input2_id)
	{
		var fn='if(this.value!=document.getElementById("'+input2_id+'").value){this.style.background="#FAA0B6";}else{this.style.background="#5FFA5A";}';
		
		return t.add2Attribute(input1,'onkeyup',fn);
	}
	
	this.is_strong=function(str)
	{
		var strength=0;
		if(str.length>6)
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
		return strength
	}
	
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
	
	this.strength_checker=function(input)
	{
		var fn='jsi.strong_enough(this)';
		return t.add2Attribute(input,'onkeyup',fn);
	}
	
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
	
	this.mail_checker=function(input)
	{
		var fn='jsi.is_mail(this)';
		return t.add2Attribute(input,'onblur',fn);
	}
}













