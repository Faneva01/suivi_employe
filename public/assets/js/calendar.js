document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('employe-calendar');
    const chartEl = document.getElementById('typeCongeChart');
    const modalEl = document.getElementById('calendarCongeModal');
    const formEl = document.getElementById('calendarCongeForm');
    const feedbackEl = document.getElementById('calendarFormFeedback');
    const modalFeedbackEl = document.getElementById('calendarModalFeedback');
    const dateDebutEl = document.getElementById('calendarDateDebut');
    const dateFinEl = document.getElementById('calendarDateFin');
    const submitButtonEl = document.getElementById('calendarSubmitButton');
    const closeButtonEl = document.getElementById('calendarModalCloseButton');
    const cancelButtonEl = document.getElementById('calendarCancelButton');
    const calendarConfig = window.calendarConfig || {};

    if (!calendarEl || typeof FullCalendar === 'undefined') {
        return;
    }

    const formatDateInput = function (dateObject) {
        const year = dateObject.getFullYear();
        const month = String(dateObject.getMonth() + 1).padStart(2, '0');
        const day = String(dateObject.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    };

    const openCalendarModal = function (startDate, endDate) {
        if (!modalEl) {
            return;
        }

        if (dateDebutEl) {
            dateDebutEl.value = startDate;
        }

        if (dateFinEl) {
            dateFinEl.value = endDate;
        }

        modalEl.classList.add('show');
    };

    const closeCalendarModal = function () {
        if (!modalEl) {
            return;
        }

        modalEl.classList.remove('show');
    };

    const setFeedback = function (type, message) {
        [feedbackEl, modalFeedbackEl].forEach(function (element) {
            if (!element) {
                return;
            }

            element.className = `calendar-feedback calendar-feedback-${type}`;
            element.textContent = message;
        });
    };

    const updateCsrfToken = function (responseData) {
        if (!formEl || !responseData.csrf_token || !responseData.csrf_hash) {
            return;
        }

        const csrfInputEl = formEl.querySelector(`input[name="${responseData.csrf_token}"]`);

        if (csrfInputEl) {
            csrfInputEl.value = responseData.csrf_hash;
        }
    };

    const calendarInstance = new FullCalendar.Calendar(calendarEl, {
        locale: calendarConfig.locale || 'fr',
        initialView: 'timeGridWeek',
        firstDay: 1,
        nowIndicator: true,
        selectable: true,
        selectMirror: true,
        allDaySlot: true,
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
        },
        events: calendarConfig.events || [],
        dateClick: function (info) {
            openCalendarModal(info.dateStr, info.dateStr);
        },
        select: function (info) {
            const endDate = new Date(info.end);
            endDate.setDate(endDate.getDate() - 1);

            openCalendarModal(info.startStr, formatDateInput(endDate));
        },
        eventDidMount: function (info) {
            const props = info.event.extendedProps || {};
            const tooltipParts = [
                props.type_libelle || info.event.title,
                props.statut ? `Statut: ${props.statut}` : '',
                props.nb_jours ? `Durée: ${props.nb_jours} jour(s)` : '',
            ].filter(Boolean);

            info.el.setAttribute('title', tooltipParts.join(' | '));
        },
    });

    calendarInstance.render();

    if (chartEl && typeof Chart !== 'undefined') {
        const typeStats = calendarConfig.typeStats || [];

        new Chart(chartEl, {
            type: 'bar',
            data: {
                labels: typeStats.map(function (item) {
                    return item.libelle;
                }),
                datasets: [{
                    label: 'Nombre de demandes',
                    data: typeStats.map(function (item) {
                        return item.total_demandes;
                    }),
                    backgroundColor: ['#2d5a3d', '#5fa876', '#b8750a', '#1a4f7a', '#c0392b'],
                    borderRadius: 10,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });
    }

    if (closeButtonEl) {
        closeButtonEl.addEventListener('click', closeCalendarModal);
    }

    if (cancelButtonEl) {
        cancelButtonEl.addEventListener('click', closeCalendarModal);
    }

    if (modalEl) {
        modalEl.addEventListener('click', function (event) {
            if (event.target === modalEl) {
                closeCalendarModal();
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeCalendarModal();
        }
    });

    if (!formEl) {
        return;
    }

    formEl.addEventListener('submit', async function (event) {
        event.preventDefault();

        if (submitButtonEl) {
            submitButtonEl.disabled = true;
            submitButtonEl.textContent = 'Envoi en cours...';
        }

        try {
            const response = await fetch(formEl.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(formEl),
            });

            const responseData = await response.json();
            updateCsrfToken(responseData);

            if (!response.ok || !responseData.success) {
                throw new Error(responseData.message || 'Impossible d\'enregistrer cette demande.');
            }

            if (responseData.calendar_event) {
                calendarInstance.addEvent(responseData.calendar_event);
            }

            setFeedback('success', responseData.message);
            closeCalendarModal();

            window.setTimeout(function () {
                window.location.reload();
            }, 900);
        } catch (error) {
            setFeedback('error', error.message);
        } finally {
            if (submitButtonEl) {
                submitButtonEl.disabled = false;
                submitButtonEl.textContent = 'Envoyer la demande';
            }
        }
    });
});
