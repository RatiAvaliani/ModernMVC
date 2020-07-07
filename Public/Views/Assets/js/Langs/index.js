import langsList from "/ModernMVC//Public/langs/lang.js";
import Vea from "/ModernMVC/Public/Views/Assets/js/Modules/Vea.js";

class langs {

    static tags = {
        "changeLang"     : "change_lang",
        "dataLangType"   : "data-lang-type",
        "dataLangId"     : "data-lang-id",
        "langsList"      : "langs_list",
        "editButton"     : "edit-button",
        "removeButton"   : "remove-button",
        "bootstrapModal" : "bootstrap-modal",
        "modal"          : "modal-click"
    };

    exportOrder = [
        'id',
        'lang',
        'name',
        'content',
        'status',
        'edit',
        'remove'
    ];

    constructor () {
        $(`.${langs.tags.bootstrapModal}`).load('/ModernMVC/Public/Views/default/bootstrapModal.html');

        this.getCurrentLangType();

        let langName = this.langNameIterator();
        let langContent = langName.next();

        while (langContent['done'] !== true) {
            langContent['promise'].then((langName) => {
                this.loadeTabs(langName);
            });
            langContent = langName.next();
        }
    }

    getCurrentLangType () {
        let cookies = document.cookie;
        let langIndex = cookies.indexOf('currentLang');

        if (langIndex === -1) this.currentLang = Object.keys(langsList)[0]; return;

        this.currentLang = cookies.slice(langIndex+12, cookies.length);
    }

    get (langName=null) {
        if (typeof langName !== "string") throw new Error('Passed element is empty');

        return langsList[this.currentLang][langName];
    }

    loadeActions () {
        $(`.${langs.tags.editButton}`).click(function () {
            $('.modal-title').text('edit');
            (new Vea()).select('.modal-body').append('input').enter();
            $(`#${langs.tags.modal}`).click();
        });

        $(`.${langs.tags.removeButton}`).click(function () {});

        return this;
    }

    selectActiveLang () {
        $(`.${langs.tags.changeLang}`).on('click', function () {
            $(`.${langs.tags.changeLang}`).removeClass('active');

            $(`.${langs.tags.langsList}`).addClass('d-none');
            $(`.${langs.tags.langsList}[${langs.tags.dataLangType}="${$(this).attr(langs.tags.dataLangType)}"]`).removeClass('d-none');
            $(this).addClass('active');
        });

        return this;
    }

    loadeTabs (langName=null) {
        if (langName === null) return;

        (new Vea())
            .select('.nav-tabs')
            .append('li')
            .addClass('nav-item')
            .append('a')
            .addClass(`nav-link ${langs.tags.changeLang}`)
            .addAttr('href', '#')
            .addAttr(langs.tags.dataLangType, langName)
            .text(langName)
            .enter();

        let activeLangType = $('.nav-item:nth-child(1)').find('a').addClass('active').attr(langs.tags.dataLangType);

        let tbody = (new Vea())
            .select('.table')
            .append('tbody')
            .addClass(`${langs.tags.langsList} ${activeLangType.trim() !== langName.trim() ? "d-none" : ""}` )
            .addAttr(langs.tags.dataLangType, langName);

        for (let cont of langsList[langName]) {
            tbody = tbody.append('tr');
            for (let expOrder of this.exportOrder) {
                if (cont[expOrder] === undefined) {
                    tbody.append('th')
                        .append('button')
                        .addAttr('type', 'button')
                        .addClass(`btn ${expOrder === "edit" ? `btn-info ${langs.tags.editButton}` : `btn-danger ${langs.tags.removeButton}` }`)
                        .addAttr(langs.tags.dataLangType, langName)
                        .addAttr(langs.tags.dataLangId, cont['id'])
                        .text(expOrder);
                } else if (expOrder === 'lang') {
                    tbody.append('th').text(langName);
                } else {
                    tbody.append('th').text(cont[expOrder]);
                }
            }
        }

        tbody.enter();
        this.selectActiveLang().loadeActions();

        return true;
    }

    langNameIterator () {
        let [count, list] = [0, Object.keys(langsList)];

        let iterator = {
            [Symbol.iterator] : () => {
                return {
                    next : () => {
                        if (count > list.length) {
                            return {
                                done : true
                            };
                        }

                        return {
                            "promise" : new Promise((res) => {
                                res(list[count]);
                            }),
                            "value" : {
                                "name"   : list[count],
                                "values" : langsList[list[count]]
                            },
                            "done"  : false,
                            "count" : count++
                        }
                    }
                }
            }
        }

        return iterator[Symbol.iterator]();
    }

    loadeTable () {

    }

    reed () {

    }

    update () {

    }

    edit () {

    }

    delete () {

    }
}

$(document).ready(() => {
    new langs();
});

