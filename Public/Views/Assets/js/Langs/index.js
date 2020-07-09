import langsList from "/ModernMVC//Public/langs/lang.js";
import Vea from "/ModernMVC/Public/Views/Assets/js/Modules/Vea.js";

class langs {

    static tags = {
        "changeLang"     : "change_lang",
        "dataLangType"   : "data-lang-type",
        "dataLangId"     : "data-lang-id",
        "dataLangIndex"  : "data-lang-index",
        "langsList"      : "langs_list",
        "editButton"     : "edit-button",
        "removeButton"   : "remove-button",
        "bootstrapModal" : "bootstrap-modal",
        "modal"          : "modal-click",
        "modalSave"      : "modal-save"
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

    editOrder = [
        'name',
        'content'
    ];

    constructor () {
        this.set();
    }

    set (update=null) {
        (async() => {
            await this.setTablas(update);
            this.loadBootstrap();
            this.loadActions();
        })();
    }

    loadBootstrap () {
        $(`.${langs.tags.bootstrapModal}`).load('/ModernMVC/Public/Views/default/bootstrapModal.html');
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

    loadActions () {
        $(`.${langs.tags.editButton}`).on('click', ev => {
            let _this = $(ev.currentTarget);

            this.editAction(
                _this.attr(langs.tags.dataLangType),
                _this.attr(langs.tags.dataLangId),
                _this.attr(langs.tags.dataLangIndex)
            );
        });

        $(`.${langs.tags.removeButton}`).click((ev) => {
            let _this = $(ev.currentTarget);
            this.delete(_this.attr(langs.tags.dataLangId));
        });
    }

    inputValidator () {
        $('.input-form').validate({
            rules: {
                content : {
                    required: true,
                    minlength: 4
                },
                name : {
                    required: true,
                    minlength: 4
                }
            },
            submitHandler: (element) => {
                $(element).ajaxSubmit();
            },
            errorPlacement : (error, element) => {
                element.parent().parent().append(error);
            }
        });
    }

    editAction (langType=null, langId=null, langsIndex=null) {

        var inputElements = (new Vea()).select('.modal-body');
        $('.modal-title').text('Edit');
        (new Vea()).select('.modal-body').reset();

        (new Vea()).select('.modal-body').append('form').addAttr('method', 'post').addAttr('action', 'api/edit').addClass('input-form col-12').enter();

        let savedContent = langsList[langType][langsIndex];
        for (let input of this.editOrder) {

            if (savedContent[input] === undefined) continue;
            let Content = savedContent[input];

            (new Vea()).select('.input-form').append('div').addClass('input-group col-6 float-left')
                .append('div').addClass('input-group-prepend mt-2')
                .append('span').addClass('input-group-text').addAttr('id', 'inputGroup-sizing-default').text(input).endElement()
                .append('input').addAttr('name', input).addClass('form-control').addAttr('value', Content).addAttr('type', 'text').addAttr('aria-label', 'Default').addAttr('aria-describedby', 'inputGroup-sizing-default').enter();
        }

        (new Vea()).select('.input-form')
            .append('input').addAttr('type', 'hidden').addAttr('name', 'langId').addAttr('value', savedContent.id)
            .append('input').addClass('btn btn-secondary col-12 mt-5').addAttr('type', 'submit').addAttr('value', 'Save').enter();

        this.inputValidator();

        $(`#${langs.tags.modal}`).click();
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

    setTablas (update=null) {
        this.getCurrentLangType();

        let langName = this.langNameIterator();
        let langContent = langName.next();

        while (langContent['done'] !== true) {
            langContent['promise'].then((langName) => {
                this.loadeTabs(langName, update);
            });
            langContent = langName.next();
        }
        return true;
    }

    loadeTabs (langName=null, update=null) {
        if (langName === null) return;

        let nav = (new Vea())
            .select('.nav-tabs')
            .append('li').addClass('nav-item')
            .append('a').addClass(`nav-link ${langs.tags.changeLang}`).addAttr('href', '#').addAttr(langs.tags.dataLangType, langName).text(langName);

        if (update !== null) nav.reset();

        nav.enter();

        let activeLangType = $('.nav-item:nth-child(1)').find('a').addClass('active').attr(langs.tags.dataLangType);

        let tbody = (new Vea())
            .select('.table')
            .append('tbody')
            .addClass(`${langs.tags.langsList} ${activeLangType.trim() !== langName.trim() ? "d-none" : ""}` )
            .addAttr(langs.tags.dataLangType, langName);

        let i = 0;
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
                            .addAttr(langs.tags.dataLangIndex, i.toString())
                            .text(expOrder);
                } else if (expOrder === 'lang') {
                    tbody.append('th').text(langName);
                } else {
                    tbody.append('th').text(cont[expOrder]);
                }
            }
            i++;
        }

        tbody.enter();
        this.selectActiveLang();

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

    read () {

    }

    update () {

    }

    edit () {

    }

    delete (langId=null) {
        if (langId === null) throw new Error('Passed element empty');

        $.ajax({
            url: "api/remove",
            type: "delete",
            data: {
                "langId" : langId
            }
        }).done(() => {
            console.log('removed reload');
        });
    }
}

$(document).ready(() => {
    new langs();
});

