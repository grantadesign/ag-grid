import "../../../../ag-grid/src/styles/ag-grid.scss";
import "../../../../ag-grid/src/styles/theme-fresh.scss";
import "../../../../ag-grid/src/styles/theme-blue.scss";
import "../../../../ag-grid/src/styles/theme-dark.scss";
import "../../../../ag-grid/src/styles/theme-bootstrap.scss";
import "../../../../ag-grid/src/styles/theme-material.scss";

import "../../../../ag-grid/src/styles/ag-theme-material.scss";
import "../../../../ag-grid/src/styles/ag-theme-fresh.scss";
import "../../../../ag-grid/src/styles/ag-theme-dark.scss";
import "../../../../ag-grid/src/styles/ag-theme-blue.scss";
import "../../../../ag-grid/src/styles/ag-theme-bootstrap.scss";
import "../../../../ag-grid/src/styles/ag-theme-balham.scss";
import "../../../../ag-grid/src/styles/ag-theme-balham-dark.scss";

declare var HMR: boolean;

if (HMR) {
    (<any>global).hot = true;
    require("webpack-hot-middleware/client?path=/dev/ag-grid-enterprise-bundle/__webpack_hmr&reload=true");
}

export * from "../../../../ag-grid/src/ts/main";

import "../../../../ag-grid-enterprise/src/main.ts";
