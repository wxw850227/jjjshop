/*判断哪些规格可以选*/
export const judgeSelect = (list,_index,productSpecArr,productSku) => {
	
	/*大类*/
	for (let i = 0, count = list.length; i < count; i++) {
		/*小类*/
		for (let j = 0; j < list[i].spec_items.length; j++) {
			let item = list[i].spec_items[j];
			if(i!=_index){
				item.disabled = hasSpecId(i,item.item_id,productSpecArr,productSku);
			}
		}
	}
}

/*判断有没有规格ID*/
function hasSpecId(index,id,productSpecArr,productSku){
	let disabled=false;
	let reg='';
	for(let p=0;p<productSpecArr.length;p++){
		if(p!=index){
			if(productSpecArr[p]!=null){
				reg+=productSpecArr[p]+'_';
			}else{
				reg+='[0-9]*_';
			}
		}else{
			reg+=id+'_';
		}
	}
	reg=reg.substr(0,reg.length-1);
	let re=new RegExp(reg,'g');
	for (let s = 0; s < productSku.length; s++) {
		let ids=productSku[s].join('_');
		disabled=re.test(ids);
		if(disabled){
			break;
		}
	}
	return !disabled;
}