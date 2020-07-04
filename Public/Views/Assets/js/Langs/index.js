import {ss} from "./Logs.js";

class langs {

    constructor (fileData=null) {
        this.getFileContent();
    }

    getFileContent () {
        this.langs = d3.json('/ModernMVC/Public/langs/lang.json').then(this.loadFileData);

    }

    loadFileData (fileData = null) {
        if (fileData === null || fileData === "" || fileData === "{}") throw new Error('passed file data is empty');

        for (const lang in fileData) {

        }

        /*d3.select('tbody').selectAll('tr').data(fileData).enter().append("tr").select((s) => {
            console.log(s);
            //d3.selectAll('tr').data(fileData).append('th').text((d) => d.name);
        }).text((d) => {
            console.log(d);
        });*/
        //d3.select('table > tbody > tr').selectAll('th').data([54, 52344, 55564, 53424, 5334, 62]).append('th').text(function(d) { return d; })
    }



}

$(document).ready(() => {
    new langs();
});

