$.fn.gridOmRacun = function(property){
  let queryPage = window.location.search.replace('?','').split('&').find(item => item.includes('page'));
  if(queryPage !== undefined){
    queryPage = queryPage.split('=')
  }else{
    queryPage = []
  }
  let dtTablePage = queryPage[1] == undefined ? 1 : queryPage[1];
	let tableID = this;
	let searchItems = [];
	let dtTableFilter = '';
	let displaySearchItems = [];
	let isSearch = 0;
	let dtURLAction = [];
	let countTD = 0;
	let countSelectedTD = 0;
	let exportColumns = [];
	$.setTable(tableID,property,dtTablePage);
	countTD = $.countTD(tableID, property);
	exportColumns['display'] = [];
  exportColumns['columns'] = [];
	$.each(property.colModel, function (key, value) {
		if(value.export==true){
			exportColumns['display'].push(value.display);
			exportColumns['columns'].push(value.name);
		}
	});
	if(property.searchItems.length > 0){
		$.each(property.searchItems,function(key,propName){

			if(propName.type=='text'){
				searchItems[propName.name] = 'string';
				displaySearchItems[propName.name] = propName.display;
			}
			if(propName.type=='number'){ 
				searchItems[propName.name] = 'numeric';
				displaySearchItems[propName.name] = propName.display;
			}
			if(propName.type=='select'){
				searchItems[propName.name] = 'select';
				displaySearchItems[propName.name] = propName.display;
				$.each(propName.option,function(key2,optionValue){
					displaySearchItems[propName.name+optionValue.value] = optionValue.title;
				});
			}
			if(propName.type=='date'){
				searchItems[propName.name+"[start]"] = 'date';
				searchItems[propName.name+"[end]"] = 'date';
				displaySearchItems[propName.name+"[end]"] = propName.display;
				displaySearchItems[propName.name+"[start]"] = propName.display;
			}
		});
	}
	$.each(property.buttonAction,function(key,url){
		dtURLAction[url.action]=url.url;
	});
	
	$(document).on('click','.'+$(tableID).attr('id')+'-pagination a.page-link',function(){
		dtTablePage = $(this).attr('data-page');
		if(dtTablePage!=='' && dtTablePage!==undefined){
      query = window.location.origin + window.location.pathname +"?page="+dtTablePage
      window.history.replaceState( {} , '', query );
      $(`.${$(tableID).attr('id')}-pagination li.page-item`).removeClass('active')
      $(this.parentNode).addClass('active')
			let dtTableLimit = $('.'+$(tableID).attr('id')+'-dtTable-limit').val();
			$.updateTable(tableID, property, dtTableLimit,dtTablePage,dtTableFilter);
		}
		
	});
	$(document).on('change','.'+$(tableID).attr('id')+'-dtTable-limit',function(){
		let dtTableLimit = $(this).val();
		dtTablePage = 1;
		$.updateTable(tableID, property, dtTableLimit,dtTablePage,dtTableFilter);
		
	});
	$(document).on('submit','#'+$(tableID).attr('id')+'-dtTable-form',function(e){
		$("#"+$(tableID).attr('id')+"-searchModal").modal('hide');
		dtTablePage = 1;
		e.preventDefault();
		let form = $(this).serializeArray();
		dtTableFilter = '';
		$.each(form,function(i,field){
      if(searchItems[field.name]!==undefined){
				if(field.value!=''){
					if(searchItems[field.name]=='date'){
            if(field.name.includes('start')){
              let fieldName = field.name.replace('[start]','');
              dtTableFilter += `&${fieldName}[gte]=${field.value}`
            }else{
              let fieldName = field.name.replace('[end]','');
              dtTableFilter += `&${fieldName}[lte]=${field.value}`
            }
            isSearch++;				
          }else if(searchItems[field.name]=='select'){
            dtTableFilter += `&${field.name}=${field.value}`
						isSearch++;
          }else{
						dtTableFilter += `&${field.name}[lse]=${field.value}`
						isSearch++;
					}
				}
			}
    });
    if(dtTableFilter !== ''){
      let dtTableLimit = $('.'+$(tableID).attr('id')+'-dtTable-limit').val();
      $.setFilterNav(tableID,property,form,displaySearchItems,searchItems);
      $.updateTable(tableID,property,dtTableLimit,dtTablePage,dtTableFilter,isSearch);
    }
		
	});
	$(document).on('click','.'+$(tableID).attr('id')+'-filter-button',function(){
    dtTablePage = 1;

		let val = $(this).attr('data-name');
    $("#"+$(tableID).attr('id')+"-form-data-"+val).val('');
    isSearch--;

		let dtTableLimit = $('.'+$(tableID).attr('id')+'-dtTable-limit').val();
		let form = $("#"+$(tableID).attr('id')+'-dtTable-form').serializeArray();
		dtTableFilter = '';
		$.each(form,function(i,field){
			if(searchItems[field.name]!==undefined){
				if(field.value!=''){
					if(searchItems[field.name]=='date'){
            if(field.name.includes('start')){
              let fieldName = field.name.replace('[start]','');
              dtTableFilter += `&${fieldName}[gte]=${field.value}`
            }else{
              let fieldName = field.name.replace('[end]','');
              dtTableFilter += `&${fieldName}[lte]=${field.value}`
            }
            isSearch++;				
          }else if(searchItems[field.name]=='select'){
            dtTableFilter += `&${field.name}=${field.value}`
						isSearch++;
          }else{
						dtTableFilter += `&${field.name}[lse]=${field.value}`
						isSearch++;
					}
				}
			}
		});
		$.setFilterNav(tableID,property,form,displaySearchItems,searchItems);
		$.updateTable(tableID,property,dtTableLimit,dtTablePage,dtTableFilter,isSearch);
		$(this).remove();
		
	});
	$(document).on("click","."+$(tableID).attr('id')+"-filter-clear",function(){
		isSearch = 0;
		let dtTableLimit = $('.'+$(tableID).attr('id')+'-dtTable-limit').val();
		dtTablePage = 1;
		dtTableFilter = '';
		$("#"+$(tableID).attr('id')+'-dtTable-form').trigger('reset');
		$("."+$(tableID).attr('id')+'-filter-button').remove();
		$("."+$(tableID).attr('id')+'-filter-clear').remove();
		$.updateTable(tableID,property,dtTableLimit,dtTablePage,dtTableFilter,isSearch);
		
	});
	$(document).on("click","."+$(tableID).attr('id')+'-select-all-th', (e) => {
		let tagInput = $(e.currentTarget).find("input");
		if($(e.currentTarget).hasClass("class-selected")){
			tagInput.prop('checked',false);
			$(e.currentTarget).removeClass("class-selected");
			$("."+$(tableID).attr('id')+"-select-tr").removeClass("class-selected");
			$("."+$(tableID).attr('id')+"-selected-"+property.selectID).prop('checked',false);
			$("."+$(tableID).attr('id')+"-select-tr").closest("tr").removeClass($(tableID).attr('id')+'-add-class').removeClass('tr-selected');
			countSelectedTD = 0;	
		}else{
			tagInput.prop('checked',true);
			$(e.currentTarget).addClass("class-selected");
			$("."+$(tableID).attr('id')+"-select-tr").addClass("class-selected");
			$("."+$(tableID).attr('id')+"-selected-"+property.selectID).prop('checked',true);
			$("."+$(tableID).attr('id')+"-select-tr").closest("tr").addClass($(tableID).attr('id')+'-add-class').addClass('tr-selected');
			countSelectedTD = countTD;
		}
	});
	$(document).on("click", "." + $(tableID).attr('id') + '-action', (e) => {
    let target = e.currentTarget.getAttribute('data-type');
    
    if(target == 'add'){
      let funct = target;
      if(property.actionStore !== undefined){
        try{
          property.actionStore[funct]()
        }catch(err){
          window[funct]();
        }
      }else{
        window[funct]();
      }
    }else{
      let message = e.currentTarget.getAttribute('data-message');
      let form = $('#' + $(tableID).attr('id') + '-dtTable-form-select').serializeArray();
      let formData = {};
      let arrData = [];
      let arrJson = [];
      let dtTableLimit = $('.'+$(tableID).attr('id')+'-dtTable-limit').val();
      formData['field'] = property.selectID;
      $.each(form, function (i, val) {
        let data = JSON.parse(val.value);
        arrData[i] = data.id;
        arrJson[i] = data.json;
      });	
      formData['item'] = JSON.stringify(arrData);
      
      if(arrData.length < 1){
        Swal.fire('Perhatian!','Tidak ada data yang dipilih','warning');
        return false;
      }
      
      let funct = target;
      url = dtURLAction[funct];
      let data = {
        url,
        formData,
        arrJson,
        tableID,
        message
      }
      if(property.actionStore !== undefined){
        try{
          property.actionStore[funct](data)
        }catch(err){
          window[funct](data);
        }
      }else{
        window[funct](data);
      }
    }
	});	
	$(document).on("click","."+$(tableID).attr('id')+"-select-tr", (e) => {
    countTD = $.countTD(tableID, property);
		let tagInput = $(e.currentTarget).find("input");
		if($(e.currentTarget).hasClass("class-selected")){
			tagInput.prop('checked',false);
			$(e.currentTarget).removeClass("class-selected");
			$(e.currentTarget).closest("tr").removeClass($(tableID).attr('id')+'-add-class').removeClass('tr-selected');
			if(property.multiSelect==true){
        countSelectedTD--;
				if(countSelectedTD<countTD){
					$("#" + $(tableID).attr('id') + "-selecting-all").prop('checked', false);
					$("." + $(tableID).attr('id') + "-select-all-th").removeClass("class-selected");
				}
			}			
		}else{
			if(property.multiSelect==false || property.multiSelect==undefined){
				$("."+$(tableID).attr("id")+"-selected-"+property.selectID).prop('checked',false);
				$("."+$(tableID).attr('id')+"-select-tr").removeClass("class-selected");
				$("."+$(tableID).attr('id')+"-select-tr").closest("tr").removeClass($(tableID).attr('id')+'-add-class').removeClass('tr-selected');
			}
			tagInput.prop('checked',true);
			$(e.currentTarget).addClass("class-selected");
			$(e.currentTarget).closest("tr").addClass($(tableID).attr('id')+'-add-class').addClass('tr-selected');
			if (property.multiSelect == true) {
        countSelectedTD++;
				if (countSelectedTD == countTD) {
					$("#" + $(tableID).attr('id') + "-selecting-all").prop('checked', true);
					$("." + $(tableID).attr('id') + "-select-all-th").addClass("class-selected");
					$("."+$(tableID).attr('id')+"-select-tr").closest("tr").addClass($(tableID).attr('id')+'-add-class').addClass('tr-selected');
				}
			}
		}
	});
	$.refreshGrid = function ($ID, url = '') {

		let divID = $(`div[id="${$ID}"]`);
		let dtTableLimit = $('.' + $ID + '-dtTable-limit').val();
		if(url != ''){
			property.url = url
		}
		$.updateTable(divID, property, dtTableLimit, dtTablePage, dtTableFilter, isSearch);
	}
	$(document).on('click',`.${$(tableID).attr('id')}-sort`,function(){
		let dtTableLimit = $('.'+$(tableID).attr('id')+'-dtTable-limit').val();
		let dataSort = ($(this).attr('data-sort')==undefined || $(this).attr('data-sort')=='' ? 'asc' : $(this).attr('data-sort'));
		let dataField = $(this).attr('data-field');
		property.sortName = dataField;
		property.sortOrder = dataSort;
		$.updateTable(tableID,property,dtTableLimit,dtTablePage,dtTableFilter,isSearch);
		$(`.${$(tableID).attr('id')}-sort`).attr('data-sort','');
		$(`.${$(tableID).attr('id')}-sort i`).removeClass('text-primary bx bx-sort-z-a bx-sort-a-z').addClass('text-muted bx bx-sort');
		if(dataSort=='desc'){
			$(this.children[0].children[1]).removeClass('text-muted bx bx-sort');
			$(this.children[0].children[1]).addClass('text-primary bx bx-sort-z-a');
			$(this).attr('data-sort','asc');
		}else if(dataSort=='asc'){
			$(this.children[0].children[1]).removeClass('text-muted bx bx-sort');
			$(this.children[0].children[1]).addClass('text-primary bx bx-sort-a-z');
			$(this).attr('data-sort','desc');
		}	
	});
	$(document).on('change',`#${$(tableID).attr('id')}-search-text`,function(){
		let valSearch = $(`#${$(tableID).attr('id')}-search-text`).val()
		dtTablePage = 1;
		dtTableFilter = `&search=${valSearch}`
		isSearch = valSearch != '';
		let dtTableLimit = $('.'+$(tableID).attr('id')+'-dtTable-limit').val();
		$.updateTable(tableID,property,dtTableLimit,dtTablePage,dtTableFilter,isSearch);
	});
}

$.setTable = async function(t,property,dtTablePage){
	property.method = 'GET';
	property.dataType = 'json';
	let dtTableLimit = 25;
	let defaultLimit = [25,50,100,200];
	
	let dtTbody = '';
	let dtThead = '';
	let dtExtends = '';
	let index = 0;
	let limitSection = $.setLimit(t,defaultLimit,dtTableLimit);
	let pagination = '';
	let dtOrderHead = [];
	let searchButton = '';
	let modalSearch = '';
	let actionButton = '';

	$.each(property.buttonAction,function(i,value){
		actionButton += "<button class='btn btn-light-primary btn-sm mb-1 align-items-center " + $(t).attr('id') + "-action mr-1' type='button' id='"+$(t).attr('id')+value.action+"' title='"+value.display+"' data-url='"+value.url+"' data-type='"+value.action+"' data-message='"+value.message+"'><i class='"+(value.icon==undefined ? '' : value.icon)+"'></i> <span>"+value.display+"</span></button>";
  });
  
  searchButton = `
    <div class="row">
      <div class="col-sm-12 col-md-12 d-flex flex-wrap align-items-center">
        ${actionButton}
      </div>
      <div class="col-sm-12 col-md-12 d-flex flex-wrap" id="${$(t).attr('id')}-search-result"></div>
		</div>`;
	
	if(property.searchItems.length > 0){
		let modalSearchBody = '';
		$.each(property.searchItems, function(key,value){
			modalSearchBody = modalSearchBody+'<div class="form-group">';
			if(value.type=='text'){
				modalSearchBody = modalSearchBody+'<label>'+value.display+'</label>';
				modalSearchBody = modalSearchBody+'<input type="text" name="'+value.name+'" class="form-control" id="'+$(t).attr('id')+'-form-data-'+value.name+'" />';
			}else if(value.type=='number'){
				let mdMin = '';
				let mdMax = '';
				if(typeof value.min !== "undefined"){
					mdMin = 'min="'+value.min+'"';
				}
				if(typeof value.max !== "undefined"){
					mdMax = 'max="'+value.max+'"';
				}
				modalSearchBody = modalSearchBody+'<label>'+value.display+'</label>';
				modalSearchBody = modalSearchBody+'<input type="number" name="'+value.name+'" class="form-control" '+mdMin+' '+mdMax+' id="'+$(t).attr('id')+'-form-data-'+value.name+'"/>';
			}else if(value.type=='select'){
				let mdOption = '<option value="">---</option>';
				if(typeof value.option !=="undefined"){
					$.each(value.option ,function(key,value2){
						mdOption = mdOption+'<option value="'+value2.value+'">'+value2.title+'</option>';
					});
				}
				modalSearchBody = modalSearchBody+'<label>'+value.display+'</label>';
				modalSearchBody = modalSearchBody+'<select name="'+value.name+'" class="form-control" id="'+$(t).attr('id')+'-form-data-'+value.name+'">';
				modalSearchBody = modalSearchBody+mdOption+'</select>';
			}else if(value.type=='date'){
				modalSearchBody = modalSearchBody+'<label>'+value.display+'</label>';
				modalSearchBody = modalSearchBody+'<div class="row">';

				modalSearchBody = modalSearchBody+'<div class="col-md-6"><label>Lebih Dari</label><fieldset class="form-group position-relative has-icon-left"><input type="date" name="'+value.name+'[start]" id="'+$(t).attr('id')+'-form-data-'+value.name+'start" class="form-control" placeholder="Select Date"><div class="form-control-position"><i class="bx bx-calendar"></i></div></fieldset></div>';
				modalSearchBody = modalSearchBody+'<div class="col-md-6"><label>Kurang Dari</label><fieldset class="form-group position-relative has-icon-left"><input type="date" name="'+value.name+'[end]" id="'+$(t).attr('id')+'-form-data-'+value.name+'end" class="form-control" placeholder="Select Date"><div class="form-control-position"><i class="bx bx-calendar"></i></div></fieldset></div>';
				modalSearchBody = modalSearchBody+'</div>';
			}
			modalSearchBody = modalSearchBody+'</div>';
		});
		searchButton = `
		<div class="row">
			<div class="col-sm-12 col-md-12 d-flex flex-wrap">
				<button class="btn btn-sm btn-primary mb-1 mr-1 align-items-center" data-toggle="modal" data-target="#${$(t).attr('id')}-searchModal"><i class="bx bx-search"></i><span> Cari</span></button>
				${actionButton}
			</div>
			<div class="col-sm-12 col-md-12 d-flex flex-wrap" id="${$(t).attr('id')}-search-result"></div>
		</div>`;
		modalSearch = modalSearch+'<div class="modal" id="'+$(t).attr('id')+'-searchModal">';
			modalSearch = modalSearch+'<div class="modal-dialog modal-dialog-scrollable" role="document">';
			modalSearch = modalSearch+'<form class="form-search" id="'+$(t).attr('id')+'-dtTable-form"><div class="modal-content">';
			modalSearch = modalSearch+'<div class="modal-header">';
				modalSearch = modalSearch+'<h4 class="modal-title">'+(typeof property.searchTitle!=="undefined" ? property.searchTitle : 'Pencarian')+'</h4>';
				modalSearch = modalSearch+'<button type="button" class="close" data-dismiss="modal">&times;</button>';
				modalSearch = modalSearch+'</div>';
				modalSearch = modalSearch+'<div class="modal-body">'+modalSearchBody+'</div>';
				modalSearch = modalSearch+'<div class="modal-footer">';
				modalSearch = modalSearch+'<button type="button" class="btn btn-sm btn-light-secondary" data-dismiss="modal">Tutup</button><button type="submit" class="btn btn-sm btn-primary">Cari Data</button>';
				modalSearch = modalSearch+'</div>';
			modalSearch = modalSearch+'</div></form>';
			modalSearch = modalSearch+'</div>';
		modalSearch = modalSearch+'</div>';
	}else{
		searchButton = `
			<div class="row">
				<div class="col-sm-12 col-md-12 d-flex flex-wrap align-items-center">
					<input type="text" name="search" class="form-control mr-1 mb-1 w-25" placeholder="Tekan enter untuk mencari data" id="${$(t).attr('id')}-search-text" />
					${actionButton}
				</div>
				<div class="col-sm-12 col-md-12 d-flex flex-wrap" id="${$(t).attr('id')}-search-result"></div>
			</div>`;
	}


	$.each(property.colModel,function(key,value){
		let width = value.width == undefined ? '' : value.width;
		width = "min-width: "+width+";max-width: "+width+";";
		let alignHead = 'justify-content-start';
		if(value.align == 'center'){
			alignHead = 'justify-content-center'
		}
		if(value.align == 'right'){
			alignHead = 'justify-content-end'
		}
		if(value.sortAble==true){
			let sortIcon = '';
			let sortData = '';
			if(value.name==property.sortName){
				if(property.sortOrder=="desc"){
          sortIcon = `<i class="text-primary bx bx-sort-z-a ml-1"></i>`;
          sortData = `class="${$(t).attr('id')}-sort sortable" data-field="${value.name}" data-sort="asc" `;
				}else{
          sortIcon = `<i class="text-primary bx bx-sort-a-z ml-1"></i>`;
          sortData = `class="${$(t).attr('id')}-sort sortable" data-field="${value.name}" data-sort="desc" `;
				}
			}else{
        sortIcon = `<i class="text-muted bx bx-sort ml-1"></i>`;
        sortData = `class="${$(t).attr('id')}-sort sortable" data-field="${value.name}" data-sort="" `;
			}
      dtThead = dtThead+`<th ${sortData} title="${value.display}" style="text-align${value.align==undefined ? 'left' : value.align}; ${width}">
      <div class="d-flex align-items-center ${alignHead}"><span>${value.display}</span>${sortIcon}</div></th>` ;
		}else{
			dtThead = dtThead+'<th title="'+value.display+'" style="text-align:'+(value.align==undefined ? 'left' : value.align)+';'+width+'">'+value.display+'</th>';
		}
		dtOrderHead[index++] = value;
	});
	dtTbody = `<tbody class="${$(t).attr('id')}-tbody"><td style="text-align:center;" colspan="${dtOrderHead.length +1}"><div class="spinner-grow spinner-lg text-primary" role="status"><span class="sr-only">Loading...</span></div></td></tbody>`;
	tmpdtThead = '<thead><tr>' + dtThead + '</tr></thead>';
	dtTable = '<table class="table table-hover table-striped om-table table-sm">' + tmpdtThead + dtTbody + '</table>';
	$(t).html(dtTable);
	$(`.${$(t).attr('id')}-tbody`).html(`<td style="text-align:center;" colspan="${dtOrderHead.length +1}"><div class="spinner-grow spinner-lg text-primary" role="status"><span class="sr-only">Loading...</span></div></td>`);
	
	pagination = '<div class="col-sm-12 col-md-6 my-2 '+$(t).attr('id')+'-pagination"></div>';
	if(property.multiSelect==true){
		dtThead = `<thead class="cstm-table-head"><tr><th title='select' class='${$(t).attr('id')}-select-all-th text-center'><input type='checkbox' id='${$(t).attr('id')}-selecting-all'></th>${dtThead}</tr></thead>`;
		dtTable = `<form id="${$(t).attr('id')}-dtTable-form-select"><table class="table table-hover table-striped om-table table-sm">${dtThead}${dtTbody}</table></form>`;
	}else {
		dtThead = `<thead><tr><th title='select' class='text-center'>#</th>${dtThead}</tr></thead>`;
		dtTable = `<form id="${$(t).attr('id')}-dtTable-form-select"><table class="table table-hover table-striped om-table table-sm">${dtThead}${dtTbody}</table></form>`;
	}
  dtExtends = '<div class="row mt-1">'+limitSection+pagination+'</div>';
  dtTable = searchButton+'<div class="table-responsive">'+dtTable+'</div>';
	
	dtTable = dtTable+dtExtends+modalSearch;
  $(t).html(dtTable);
  let results = await $.getData(t,property,dtOrderHead,dtTableLimit,dtTablePage);
  pagination = $.setPagination(t,property,results.pagination);
  $(`.${$(t).attr('id')}-tbody`).html(results.tbody);
  $(`.${$(t).attr('id')}-pagination`).html(pagination);
	$("." + $(t).attr("id") + "-count-data").text(results.countData);	
}

$.getData = async function(t,property,dtOrderHead,dtTableLimit,dtTablePage,dtTableFilter='',isSearch=0){
  return new Promise( async (resolve) => {
    let url = property.url;
    let sort =  property.sortOrder == 'desc' ? `-${property.sortName}` : property.sortName
    let queryString = '?limit='+dtTableLimit+'&page='+dtTablePage+'&sort='+sort+dtTableFilter;
    if(url.includes("?")){
      queryString = '&limit='+dtTableLimit+'&page='+dtTablePage+'&sort='+sort+dtTableFilter;
    }
    let tbody = '';
    let pagination = '';
    let countData = '';	
    $.ajax({
      url: url+queryString,
      type: property.method,
      dataType: property.dataType,
      async: true,
      success: function(response){
        if(isSearch<1  && response.results.pagination.total_data==0){
          tbody = '<tr class="empty-data"><td colspan="'+ parseInt(dtOrderHead.length+1)+'" class="text-center">Data kosong</td></tr>';
        }else if(isSearch>0 && response.results.pagination.total_data==0){
          tbody = '<tr class="empty-data"><td colspan="'+ parseInt(dtOrderHead.length+1)+'" class="text-center">Data tidak ditemukan</td></tr>';
        }else{
          $.each(response.results.data,function(key,value){
            tbody = tbody+'<tr class="'+$(t).attr('id')+'-select-tr">'
            tbody = tbody+"<td style='padding-top: 8px;' class='text-center' title='select' class='text-center'><input type='checkbox' name='"+property.selectID+"' class='"+$(t).attr('id')+"-selected-"+property.selectID+"' value='"+JSON.stringify({json:value, id:value[property.selectID]})+"'></td>";
            $.each(dtOrderHead,function(key,field){
              let text = value[field.name];
              if(field.render !== undefined){
                text = field.render(text)
              }
              if(field.format !== undefined){
                text = formatText(field.format, text)
              }
              if(field.action !== undefined){
                if(field.action.function !== undefined){
                  let data = JSON.stringify(value).replace(/\"/g, '\'')
                  text = `<a href="javascript:;" onclick="${field.action.function}(${data})" class="btn btn-${field.action.class} round btn-icon btn-sm"><i class="${field.action.icon}"></i><a>`
                }
                if(field.action.url !== undefined){
                  text = `<a href="${field.action.url}" class="btn btn-${field.action.class} round btn-icon btn-sm"><i class="${field.action.icon}"></i><a>`
                }
              }
              tbody = tbody+'<td style="text-align: '+(field.align==undefined ? 'left' : field.align)+'">'+text+'</td>';
            });
            tbody = tbody+'</tr>';
          });
        }
        tbody = tbody;
        pagination = response.results.pagination;
        countData = "Menampilkan data "+response.results.pagination.start+" - "+response.results.pagination.end+" dari total "+response.results.pagination.total_data+" data";
        resolve({'tbody':tbody,'pagination':pagination,'countData':countData})
      },
      error: function(err){
        let response = err.responseJSON;
        if(response !== undefined){
          if(response.status == 401){
            window.location.reload();
          }else{
            try {
              Swal.fire(
                'Gagal!',
                'Terjadi kesalahan sistem.',
                'error'
              );
            }catch(error){
              alert('Gagal! Terjadi kesalahan sistem.');
            }
          }
        }else{
          try {
            Swal.fire(
              'Gagal!',
              'Terjadi kesalahan sistem.',
              'error'
            );
          }catch(error){
            alert('Gagal! Terjadi kesalahan sistem.');
          }
        }
      }
    });
  })
}

$.setLimit = function(t,limitOptions,currentLimit){
	let limit = '<div class="col-sm-12 col-md-6 my-2">';
	limit = limit+'<div class="d-flex align-items-center flex-wrap"><fieldset class="form-group mb-0">';
	limit = limit+'<select class="form-control br-25 pr-0 '+$(t).attr('id')+'-dtTable-limit" style="width: 70px; height: 34px; background-position-y: center;" id="dtTable-limit">';
	$.each(limitOptions,function(key,value){
		limit = limit+'<option value="'+value+'" '+(currentLimit==value ? 'selected' : '')+'>'+value+'</option>';
	});
	limit = limit+'</select>';
	limit = limit+'</fieldset>';
	limit = limit+'<span style="margin-left:10px;font-size:12px;" class="'+$(t).attr('id')+'-count-data"></span></div>';
	limit = limit+'</div>';
	return limit;
}

$.setPagination = function(t,property,data){
	let pagination = '<nav  class="ctm-paging">';
	pagination = pagination+'<ul class="pagination pagination-borderless justify-content-end">';
	if (data.prev != '0'){
		pagination = pagination + `<li class="page-item previous"><a class="page-link ${$(t).attr('id')}-page" data-page="${data.prev}" href="javascript:;"><i class="bx bx-chevron-left"></i></a></li>`;
	}
	
	$.each(data.detail,function(key,value){
		if(value==data.current){
			pagination = pagination+`<li class="page-item active" aria-current="page"><a class="page-link" href="javascript:;">${value}</a></li>`;
		}else{
			pagination = pagination+`<li class="page-item"><a class="page-link ${$(t).attr('id')}-page" data-page="${value}" href="javascript:;">${value}</a></li>`;
		}
	});

	if(data.next){
		pagination = pagination + `<li class="page-item next"><a class="page-link ${$(t).attr('id')}-page" data-page="${data.next}" href="javascript:;"><i class="bx bx-chevron-right"></i></a></li>`;
	}
	
	pagination = pagination+'</ul>';
	pagination = pagination+'</nav>';
	return pagination;
}

$.updateTable = async function(t,property,dtTableLimit,dtTablePage,dtTableFilter,isSearch){
	property.method = 'GET';
	property.dataType = 'json';
	let dtTbody = '';
	let index = 0;
	let pagination = '';
	let dtOrderHead = [];
	$.each(property.colModel,function(key,value){
		dtOrderHead[index++] = value;
	});
	$(`.${$(t).attr('id')}-tbody`).html(`<td style="text-align:center;" colspan="${dtOrderHead.length + 1}"><div class="spinner-grow spinner-lg text-primary" role="status"><span class="sr-only">Loading...</span></div></td>`);
  let results = await $.getData(t,property,dtOrderHead,dtTableLimit,dtTablePage,dtTableFilter,isSearch);
	dtTbody = results.tbody;
	pagination = $.setPagination(t,property,results.pagination);
	$(`.${$(t).attr('id')}-tbody`).html(dtTbody);
	$(`.${$(t).attr('id')}-pagination`).html(pagination);
	$("." + $(t).attr("id") + "-count-data").text(results.countData);
	$(`.${$(t).attr('id')}-select-all-th`).removeClass('class-selected');
	$(`#${$(t).attr('id')}-selecting-all`).prop('checked', false);	
}

$.setFilterNav = function(t,property,form,displaySearchItems,searchItems){
	let filterString = '';
	$.each(form,function(i,field){
		if(displaySearchItems[field.name]!==undefined){
			if(field.value!=''){
				if(searchItems[field.name]=='select'){
					filterString = filterString+"<span class='border border-primary rounded mb-1 filter-item "+$(t).attr('id')+"-filter-button' data-name='"+field.name+"'><b>"+displaySearchItems[field.name]+"</b>: "+displaySearchItems[field.name+field.value]+" <i class='bx bx-x text-danger ml-1'></i></span>";
				}else if(searchItems[field.name]=='date'){
          let fieldName = field.name.replace('[start]','').replace('[end]','')
          if(field.name.includes('start')){
            filterString = filterString+"<span class='border border-primary rounded mb-1 filter-item "+$(t).attr('id')+"-filter-button' data-name='"+fieldName+"start'><b>"+displaySearchItems[field.name]+" Lebih Dari</b>: "+moment(field.value).format('D MMM yyyy')+" <i class='bx bx-x text-danger ml-1'></i></span>";
          }else{
            filterString = filterString+"<span class='border border-primary rounded mb-1 filter-item "+$(t).attr('id')+"-filter-button' data-name='"+fieldName+"end'><b>"+displaySearchItems[field.name]+" Kurang Dari</b>: "+moment(field.value).format('D MMM yyyy')+" <i class='bx bx-x text-danger ml-1'></i></span>";
          }
				}else{
					filterString = filterString+"<span class='border border-primary rounded mb-1 filter-item "+$(t).attr('id')+"-filter-button' data-name='"+field.name+"'><b>"+displaySearchItems[field.name]+"</b>: "+field.value+" <i class='bx bx-x text-danger ml-1'></i></span>";
				}
			}
		}
	});
	if(filterString!=''){
		filterString = filterString+'<span  class="rounded mb-1 filter-item bg-danger text-white '+$(t).attr('id')+'-filter-clear">Clear All</span>';
	}
	$("#"+$(t).attr("id")+"-search-result").html(filterString);
}

$.ajaxActionButtons = function(data){
	$.ajax({
		url: data.url,
		method: "POST",
		data: data.formData,
		success: function(response){
			if(response.status==200){
				try {
					Swal.fire(
						'Berhasil!',
						'Semua data berhasil ' + data.msg_res,
						'success'
					);
				} catch (error) {
					alert('Berhasil! Semua data berhasil ' + data.msg_res);
				}				
			}else{
        if(response.results.data.success > 0){
          try {
            Swal.fire(
              'Berhasil!',
              `${response.results.data.success} data berhasil ${data.msg_res}, ${response.results.data.failed} data gagal ${data.msg_res} `,
              'warning'
            );
          } catch (error) {
            alert('Berhasil! ' + `${response.results.data.success} berhasil ${data.msg_res}, ${response.results.data.failed} gagal ${data.msg_res}, `);
          }	
        }else{
          try {
            Swal.fire(
              'Gagal!',
              'Semua data gagal ' + data.msg_res,
              'error'
            );
          } catch (error) {
            alert('Gagal! Semua data gagal ' + data.msg_res);
          }	
        }
      }
		},
		error: function(err){
      let response = err.responseJSON;
      if(response !== undefined){
        if(response.status == 401){
          try{
            Swal.fire(
              'Gagal!',
              'Sesi sudah habis. Silahkan login terlebih dahulu.',
              'error'
            );
          }catch(error){
            alert('Gagal! Sesi sudah habis. Silahkan login terlebih dahulu.');
          }
          window.location.reload();
        }else{
          try {
            Swal.fire(
              'Gagal!',
              'Terjadi kesalahan sistem.',
              'error'
            );
          }catch(error){
            alert('Gagal! Terjadi kesalahan sistem.');
          }
        }
      }else{
        try {
          Swal.fire(
            'Gagal!',
            'Terjadi kesalahan sistem.',
            'error'
          );
        }catch(error){
          alert('Gagal! Terjadi kesalahan sistem.');
        }
      }
		}
	});
	$.refreshGrid($(data.tableID).attr('id'));
}

function formatText(type, text){
  if(type == 'number'){
    return text.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
  }
  if(type == 'numberRp'){
    return 'Rp. ' + text.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
  }
  if(type == 'date'){
    return moment(text).format('ddd, DD MMM YYYY')
  }
  if(type == 'datetime'){
    return moment(text).format('ddd, DD MMM YYYY HH:mm')
  }
}

function remove(data){
  data.msg_res = 'dihapus'
	let message = data.message === 'undefined' ? 'menghapus' : data.message;
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan '+message+' data yang dipilih?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Ya',
		cancelButtonText: 'Batal',
	}).then((result)=> {
		if(result.value){
			$.ajaxActionButtons(data);
		}
	});
}

function active(data){
  data.formData['active'] = 1
  data.msg_res = 'diaktifkan'
	let message = data.message === 'undefined' ? 'mengaktifkan' : data.message;
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan '+message+' data yang dipilih?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Ya',
		cancelButtonText: 'Batal',
	}).then((result)=> {
		if(result.value){
			$.ajaxActionButtons(data);
		}
	});	
}

function nonactive(data){
  data.msg_res = 'dinon-aktifkan'
	data.formData['active'] = 0
	let message = data.message === 'undefined' ? 'nonaktifkan' : data.message;
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan '+message+' data yang dipilih?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Ya',
		cancelButtonText: 'Batal',
	}).then((result)=> {
		if(result.value){
			$.ajaxActionButtons(data);
		}
	});
}

function accept(data){
	data.formData['approve'] = 1
	let message = (data.message=="undefined" ? 'Accept' : data.message);
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan '+message+' data yang dipilih?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Ya',
		cancelButtonText: 'Batal',
	}).then((result)=> {
		if(result.value){
			$.ajaxActionButtons(data);
		}
	});	
}

function reject(data){
	data.formData['approve'] = 0
	let message = data.message === 'undefined' ? 'Reject' : data.message;
	Swal.fire({
		title: 'Perhatian!',
		text: 'Apakah anda yakin akan '+message+' data yang dipilih?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Ya',
		cancelButtonText: 'Batal',
	}).then((result)=> {
		if(result.value){
			$.ajaxActionButtons(data);
		}
	});
}

function exportExcel(data){
	$('body').append($('<form/>')
		.attr({ 'action': data.url+'?'+data.dtTableFilter.slice(1), 'method': 'post', 'id': 'dtexport-excel-form' })
		.append($('<input/>')
			.attr({ 'type': 'hidden', 'name': 'columns', 'value': JSON.stringify(data.exportColumns['columns']) })
		).append($('<input/>')
			.attr({ 'type': 'hidden', 'name': 'display', 'value': JSON.stringify(data.exportColumns['display']) })
		).append($('<input/>')
			.attr({ 'type': 'hidden', 'name': 'sort', 'value': JSON.stringify(data.property.sortName) })
		).append($('<input/>')
			.attr({ 'type': 'hidden', 'name': 'dir', 'value': JSON.stringify(data.property.sortOrder) })
		).append($('<input/>')
			.attr({ 'type': 'hidden', 'name': 'is_export', 'value': true })
		)
	).find('#dtexport-excel-form').submit();
	$("#dtexport-excel-form").remove();
}

$.countTD = function (tableID,property){
  return tableID.children("div").children("form").children("table").children("tbody").children("tr").length;
}

