var columnDefs = [
  {headerName: "Athlete", field: "athlete", enableRowGroup: true, hide: true},
  {headerName: "Age", field: "age", enableRowGroup: true},
  {headerName: "Sport", field: "sport", enableRowGroup: true, width: 350, checkboxSelection: true},
  {headerName: "Year", field: "year", enableRowGroup: true, rowGroup: true},
  {headerName: "Gold", field: "gold", aggFunc: 'sum'},
  {headerName: "Silver", field: "silver", aggFunc: 'sum'},
  {headerName: "Bronze", field: "bronze", aggFunc: 'sum'}
];

var gridOptions = {
  defaultColDef: {
    suppressFilter: true,
    width: 150
  },
  rowBuffer: 0,
  columnDefs: columnDefs,
  enableColResize: true,
  rowModelType: 'serverSide',
  rowGroupPanelShow: 'always',
  animateRows: true,
  enableSorting: true,
  debug: true,
  suppressAggFuncInHeader: true,
  toolPanelSuppressPivotMode: true,
  toolPanelSuppressValues: true,
  rowSelection: 'multiple',
  isRowSelectable: function(rowNode) {
    return !rowNode.group;
  },
  suppressRowClickSelection: true,
  // groupSelectsChildren: true, // not supported for Server Side Row Model
  // restrict to 2 server side calls concurrently
  maxConcurrentDatasourceRequests: 2,
  cacheBlockSize: 100,
  maxBlocksInCache: 2,
  purgeClosedRowNodes: true,
  autoGroupColumnDef: {
    field: 'athlete',
    width: 500,
    // headerCheckboxSelection: true, // not supported for Enterprise Model
    cellRendererParams: {
      checkbox: true
    }
  },
  onGridReady: function(params) {
    params.api.sizeColumnsToFit();
  },
  icons: {
    groupLoading: '<img src="https://raw.githubusercontent.com/ag-grid/ag-grid-docs/master/src/javascript-grid-server-side-model/spinner.gif" style="width:22px;height:22px;">'
  }
};

// setup the grid after the page has finished loading
document.addEventListener('DOMContentLoaded', function() {
  var gridDiv = document.querySelector('#myGrid');
  new agGrid.Grid(gridDiv, gridOptions);

  // do http request to get our sample data - not using any framework to keep the example self contained.
  // you will probably use a framework like JQuery, Angular or something else to do your HTTP calls.
  agGrid.simpleHttpRequest({url: 'https://raw.githubusercontent.com/ag-grid/ag-grid-docs/master/src/olympicWinners.json'})
    .then( function(data) {
        var fakeServer = new FakeServer(data);
        var datasource = new ServerSideDatasource(fakeServer, gridOptions);
        gridOptions.api.setServerSideDatasource(datasource);
      }
    );
});