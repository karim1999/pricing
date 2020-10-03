(function(jsGrid,$,undefined){function Field(config){$.extend(!0,this,config);this.sortingFunc=this._getSortingFunc()}Field.prototype={name:"",title:"",css:"",align:"",width:100,filtering:!0,inserting:!0,editing:!0,sorting:!0,sorter:"string",headerTemplate:function(){return this.title||this.name},itemTemplate:function(value,item){return value},filterTemplate:function(){return""},insertTemplate:function(){return""},editTemplate:function(value,item){this._value=value;return this.itemTemplate(value,item)},filterValue:function(){return""},insertValue:function(){return""},editValue:function(){return this._value},_getSortingFunc:function(){var sorter=this.sorter;if($.isFunction(sorter)){return sorter}if(typeof sorter==="string"){return jsGrid.sortStrategies[sorter]}throw Error("Wrong sorter for the field \""+this.name+"\"!")}};jsGrid.Field=Field}(jsGrid,jQuery))