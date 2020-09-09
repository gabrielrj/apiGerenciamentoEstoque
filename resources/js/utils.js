$(document).ready(function (){

    try{
        inicializarBotaoFlutuante()
    }catch (e) {

    }

    try {
        inicializarSelectsEDropdown();
    } catch (e) {

    }


    try{
        inicializarCollapsible()
    }catch (e) {

    }

    try {
        inicializarTabs();
    }catch (e) {

    }

    try {
        inicializarDatePickers();
        inicializarTimePickers();
    } catch (e) {

    }


    try {
        inicializarTextAreaCharacterCounter();
    } catch (e) {

    }

    try {
        incializarModals();
    } catch (e) {

    }

    try {
        inicializarTooltip();
    } catch (e) {

    }

    try {
        inicializarSideNav();
    } catch (e) {

    }

    try {
        inicializarSlider();
    } catch (e) {

    }



}); // end of document ready

function inicializarBotaoFlutuante() {
    $('#btnHelpInfotic').floatingActionButton();
    $('#btnMenuCursos').floatingActionButton({
        direction: 'left',
    });
    $('#btnMenuEventos').floatingActionButton({
        direction: 'left',
    })
}

function inicializarTabs() {
    $('.tabs').tabs();
}

function inicializarCollapsible() {
    $('.collapsible').collapsible();
}

function incializarModals() {
    $('.modal').modal();
}

function inicializarDropdown() {
    $(".dropdown-trigger").dropdown({
        coverTrigger: false,
        inDuration: 300,
        outDuration: 225,
        constrain_width: false, // Does not change width of dropdown to that of the activator
        gutter: ($('.dropdown-content').width()*3)/2.5 + 5, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'left' // Displays dropdown with edge aligned to the left of button
    });
}

function inicializarTooltip() {
    $('.tooltipped').tooltip();
}

function inicializarSelectsEDropdown() {
    try{
        inicializarDropdown()
    }catch (e) {

    }

    try{
        $('select').formSelect();
    }catch (e) {

    }


}

function inicializarTimePickers() {
    var traducaoDosTermosDoTimePicker = {
        cancel: 'Cancelar',
        clear: 'Limpar',
        done: 'Ok'
    }

    $('.timepicker').timepicker({
        i18n: traducaoDosTermosDoTimePicker,
        showClearBtn: true,
        twelveHour : false, // 12 horas, usa AM/PM
        autoclose: false  //Fecha o timepicker automaticamente apos selecionar a hora
    })

}

function inicializarDatePickers() {
    var traducaoDosTermosDoDatePicker = {
        cancel: 'Cancelar',
        clear: 'Limpar',
        done: 'Ok',
        previousMonth: '‹',
        nextMonth: '›',
        months: [
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ],
        monthsShort: [
            'Jan',
            'Fev',
            'Mar',
            'Abr',
            'Mai',
            'Jun',
            'Jul',
            'Ago',
            'Set',
            'Out',
            'Nov',
            'Dez'
        ],

        weekdays: [
            'Domingo',
            'Segunda',
            'Terça',
            'Quarta',
            'Quinta',
            'Sexta',
            'Sábado'
        ],

        weekdaysShort: [
            'Dom',
            'Seg',
            'Ter',
            'Qua',
            'Qui',
            'Sex',
            'Sáb'
        ],

        weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S']

    };

    $(".datepicker[tipo*='periodo_antigo']").datepicker({
        i18n: traducaoDosTermosDoDatePicker,
        yearRange: [(new Date().getFullYear()) - 100, (new Date().getFullYear())],
        format: 'dd/mm/yyyy',
        maxDate: new Date(),
        showClearBtn: true,
        showMonthAfterYear: true,
    })

    $(".datepicker[tipo*='periodo_atual']").datepicker({
        i18n: traducaoDosTermosDoDatePicker,
        yearRange: [(new Date().getFullYear()), (new Date().getFullYear() + 10)],
        format: 'dd/mm/yyyy',
        minDate: new Date(),
        showClearBtn: true,
        showMonthAfterYear: true,
    })

}


function inicializarTextAreaCharacterCounter() {
    $('.materialize-textarea').characterCounter();
}

function inicializarSideNav() {
    $('.sidenav').sidenav();
}

function inicializarSlider() {
    const slider = document.querySelector('.slider');
    M.Slider.init(slider, {
        indicators: false,
        height: 550,
        transition: 500,
        interval: 6100
    });
}
