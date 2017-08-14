/**
 * Railgun example
 */

import "../css/layout.scss";

import graphiql from "./graphiql";
import CodeMirror from "codemirror";

import "codemirror-graphql/mode";


let schema = CodeMirror(document.getElementById("schema"), {
    theme: "graphiql",
    lineNumbers: true,
    mode: "graphql",
    value: document.getElementById("schemaValue").innerText
});

graphiql(schema);


let linkOpen = document.createElement("a");
linkOpen.className = "toolbar-button";
linkOpen.title = "Open/Close schema.graphqls file";
linkOpen.innerHTML = "Toggle Schema";

linkOpen.addEventListener("click", () => {
    document.getElementById("graphiql").classList.toggle("hidden");
    document.getElementById("schema").classList.toggle("hidden");
}, true);

let gql = document.querySelector("#graphiql .toolbar");
gql.appendChild(linkOpen);
