function languageIdentititesList() {
	var languageIdentititesElement = $("#language_identitites_grid")
			.kendoGrid(
					{
						dataSource : {
							pageSize: 10,
            group: {
                field: "table_name",
                dir: "desc"
            },
							transport : {
								read :{
									url : 'language_identitites',
									dataType : "json",
									type : "GET",
								},
							},
							serverFiltering: true,
						},
						pageable : {
							refresh : true,
							pageSizes : true,
						},
						selectable : "row",
						groupable: true,
						scrollable : true,
						sortable : true,
						columns: [{
			                field: "language_code",
			                title: "Language Code",
			                width: 50
			            },{
			                field: "priority",
			                title: "Language Priority",
			                width: 50
			            },{
			                field: "table_name",
			                title: "Identity ",
			                width: 50
			            },{
			                field: "identity_name",
			                title: "Identity Name",
			                width: 50
			            },{
			                field: "language_name",
			                title: "Language Name",
			                width: 100
			            }],
					});
}
