$('#external-events .bee-event').each(function() {
    $(this).data('event', {
        title: $.trim($(this).text()),
        stick: true
    });
    $(this).draggable({
        zIndex: 999,
        revert: true,
        revertDuration: 0
    });
});
$('#calendar').fullCalendar({
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
    },
    defaultDate: '2018-08-12',
    editable: true,
    droppable: true,
    events: [{
        title: 'All Day Event',
        start: '2018-08-01',
        borderColor: '#ff5722',
        backgroundColor: '#ff5722',
        textColor: '#fff'
    }, {
        title: 'Long Event',
        start: '2018-08-07',
        end: '2018-08-10',
        borderColor: '#537df9',
        backgroundColor: '#537df9',
        textColor: '#fff'
    }, {
        id: 999,
        title: 'Repeating Event',
        start: '2018-08-09T16:00:00',
        borderColor: '#ffc200',
        backgroundColor: '#ffc200',
        textColor: '#fff'
    }, {
        id: 999,
        title: 'Repeating Event',
        start: '2018-08-16T16:00:00',
        borderColor: '#00bcd4',
        backgroundColor: '#00bcd4',
        textColor: '#fff'
    }, {
        title: 'Conference',
        start: '2018-08-11',
        end: '2018-08-13',
        borderColor: '#8ac542',
        backgroundColor: '#8ac542',
        textColor: '#fff'
    }, {
        title: 'Meeting',
        start: '2018-08-12T10:30:00',
        end: '2018-08-12T12:30:00'
    }, {
        title: 'Lunch',
        start: '2018-08-12T12:00:00',
        borderColor: '#ff5722',
        backgroundColor: '#ff5722',
        textColor: '#fff'
    }, {
        title: 'Happy Hour',
        start: '2018-08-12T17:30:00',
        borderColor: '#a389d4',
        backgroundColor: '#a389d4',
        textColor: '#fff'
    }, {
        title: 'Birthday Party',
        start: '2018-08-13T07:00:00'
    }, {
        title: 'Click for Google',
        url: 'http://google.com/',
        start: '2018-08-28',
        borderColor: '#a389d4',
        backgroundColor: '#a389d4',
        textColor: '#fff'
    }],
    drop: function() {
        if ($('#drop-remove').is(':checked')) {
            $(this).remove();
        }
    }
});
