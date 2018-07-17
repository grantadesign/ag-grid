<?php
$pageTitle = "ag-Grid - Core Grid Features: Column Definitions";
$pageDescription = "ag-Grid is a feature-rich data grid supporting major JavaScript Frameworks. One such feature is Column Definitions. Columns are configured in the grid by providing a list of Column Definitions. The attributes you set on the column definitions define how the columns behave e.g. title, width etc. Free and Commercial version available.";
$pageKeyboards = "ag-Grid Column Definitions";
$pageGroup = "feature";
include '../documentation-main/documentation_header.php';
?>
    <h1>Column Definitions</h1>

    <p class="lead">
        Each column in the grid is defined using a column definition. Columns are positioned in the grid according to the order
        the ColDef's are specified in the grid options. The following example shows a simple grid with 3 columns defined:
    </p>

<snippet>
var gridOptions = {
    // define 3 columns
    columnDefs: [
        {headerName: 'Athlete', field: 'athlete'},
        {headerName: 'Sport', field: 'sport'},
        {headerName: 'Age', field: 'age'}
    ],

    // other grid options here...
}</snippet>

    <p>
        See <a href="../javascript-grid-column-properties/">Column Properties</a> for a
        list of all properties that can be applied to a column.
    </p>

    <p>
        If you want the columns to be grouped, then you include them as groups like
        the following:
    </p>

<snippet>
var gridOptions = {
    columnDefs: [
        // put the three columns into a group
        {headerName: 'Group A',
            children: [
                {headerName: 'Athlete', field: 'athlete'},
                {headerName: 'Sport', field: 'sport'},
                {headerName: 'Age', field: 'age'}
            ]
        }
    ],

    // other grid options here...
}</snippet>

    <p>
        Groups are explained in more detail in the section
        <a href="../javascript-grid-grouping-headers/">Column Groups</a>.
    </p>


    <h2 id="default-column-definitions">Custom Column Types</h2>

    <p>
        In addition to the above, the grid provides additional ways to
        help simplify and avoid duplication of column definitions. This is done through the following:
    </p>

    <ul class="content">
        <li><b>defaultColDef:</b> contains column properties all columns will inherit.</li>
        <li><b>defaultColGroupDef:</b> contains column group properties all column groups will inherit.</li>
        <li><b>columnTypes:</b> specific column types containing properties that column definitions can inherit.</li>
    </ul>

    <note>
        Default columns and column types can specify any of the <a href="../javascript-grid-column-properties/">column properties</a> available on a column.
    </note>

    <p>
        The following code snippet shows these three properties configures:
    </p>

    <snippet>
var gridOptions = {
    rowData: myRowData,

    // define columns
    columnDefs: [
        // uses the default column properties
        {headerName: 'Col A', field: 'a'},

        // overrides the default with a number filter
        {headerName: 'Col B', field: 'b', filter: 'agNumberColumnFilter'},

        // overrides the default using a column type
        {headerName: 'Col C', field: 'c', type: 'nonEditableColumn'},

        // overrides the default using a multiple column types
        {headerName: 'Col D', field: 'd', type: ['dateColumn', 'nonEditableColumn']}
    ],

    // a default column definition with properties that get applied to every column
    defaultColDef: {
        // set every column width
        width: 100,
        // make every column editable
        editable: true,
        // make every column use 'text' filter by default
        filter: 'agTextColumnFilter'
    },

    // if we had column groups, we could provide default group items here
    defaultColGroupDef: {}

    // define a column type (you can define as many as you like)
    columnTypes: {
        "nonEditableColumn": {editable: false},
        "dateColumn": {filter: 'agDateColumnFilter', filterParams: {comparator: myDateComparator}, suppressMenu:true}
        }
    }

    // other grid options here...
}</snippet>

    <p>
        When the grid creates a column it starts with the default column, then adds in anything from the column
        type, then finally adds in items from the column definition.
    </p>
    <p>
        For example, the following is an outline of the steps used when creating 'Col C' shown above:
    </p>


<snippet>
// Step 1: the grid starts with an empty merged definition
{}

// Step 2: default column properties are merged in
{width: 100, editable: true, filter: 'agTextColumnFilter'}

// Step 3: column type properties are merged in (using the 'type' property)
{width: 100, editable: false, filter: 'agNumberColumnFilter'}

// Step 4: finally column definition properties are merged in
{headerName: 'Col C', field: 'c', width: 100, editable: false, filter: 'agNumberColumnFilter'}
   </snippet>

    <p>
        The following examples demonstrates this configuration.
    </p>

<?= example('Column Definition Example', 'column-definition', 'generated', array('enterprise' => 1, 'grid' => array('height' => '100%'))) ?>

<h2>Provided Column Types</h2>

<h3>Numeric Columns</h3>

<p>
The grid provides a handy shortcut for formatting numeric columns. 
Setting the column definition type to <code>numericColumn</code> aligns the column header and contents to the right, 
which makes the scanning of the data easier for the user.
</p>

<snippet>
var gridOptions = {
    columnDefs: [
        { headerName: "Column A", field: "a" },
        { headerName: "Column B", field: "b", type: "numericColumn" }
    ]
}</snippet>

<h2 id="changing-column-headers">Updating Column Definitions</h2>

<p>
    After the grid has been initialised it may be necessary to update the column definition. It is important to understand
    that when a column is created it is assigned a copy of the column definition defined in the GridOptions. For this reason
    it is necessary to obtain the column definition directly from the column.
</p>

<p>
    The following example shows how to update a column header name after the grid has been initialised. As we want to update
    the header name immediately we explicitly invoke <code>refreshHeader()</code> via the <a href="../javascript-grid-api/">Grid API</a>.
</p>

<snippet>
// get a reference to the column
var col = gridOptions.columnApi.getColumn("colId");

// obtain the column definition from the column
var colDef = col.getColDef();

// update the header name
colDef.headerName = "New Header";

// the column is now updated. to reflect the header change, get the grid refresh the header
gridOptions.api.refreshHeader();</snippet>



<h2 id="saving-and-restoring-column-state">Saving and Restoring Column State</h2>

<p>
It is possible to save and subsequently restore the column state via the <a href="../javascript-grid-column-api//">Column API</a>.
Examples of state include column visibility, width, row groups and values.
</p>
<p>
    This is primarily achieved using the following methods:
</p>

<ul class="content">
    <li><code>columnApi.getColumnState()</code>: Returns the state of a particular column.</li>
    <li><code>columnApi.setColumnState(state)</code>: To set the state of a particular column.</li>
</ul>

<p>
    The column state used by the above methods is an array of objects that mimic the colDef's which can be converted to and from JSON.
    An example is shown below:
</p>

<snippet>
[
  {colId: "athlete", aggFunc: "sum",  hide: false, rowGroupIndex: 0,    width: 150, pinned: null},
  {colId: "age",     aggFunc: null,   hide: true,  rowGroupIndex: null, width: 90,  pinned: 'left'}
]</snippet>

<p>
    The values have the following meaning:
</p>
<ul class="content">
    <li><code>colId</code>: The ID of the column. See
        <a href="../javascript-grid-column-definitions/">column definitions</a> for explanation
        of column ID
    </li>
    <li><code>aggFunc</code>: If this column is a value column, this field specifies the aggregation function.
        If the column is not a value column, this field is null.
    </li>
    <li><code>hide</code>: True if the column is hidden, otherwise false.</li>
    <li><code>rowGroupIndex</code>: The index of the row group. If the column is not grouped, this field is null.
        If multiple columns are used to group, this index provides the order of the grouping.
    </li>
    <li><code>width</code>: The width of the column. If the column was resized, this reflects the new value.</li>
    <li><code>pinned</code>: The pinned state of the column. Can be either <code>'left'</code> or <code>'right'</code></li>
</ul>


<h2 id="column-api-example">Column API Example</h2>
<p>This section illustrates how to store and restore column state using the <a href="../javascript-grid-column-api/">Column API</a>.
</p>

<ul class="content">
    <li>hiding / showing columns as well as saving / restoring the entire state</li>
    <li><b>registering for column events, the result of which are printed to the console.</b></li>
</ul>

<?= example('Column State Example', 'column-state', 'generated', array("enterprise" => 1)) ?>

<note>
    This example also includes <a href="../javascript-grid-grouping-headers/">Column Groups</a> which are
    covered in the next section, in order to demonstrate saving and restoring the expanded state.
</note>

<?php include '../documentation-main/documentation_footer.php';?>
