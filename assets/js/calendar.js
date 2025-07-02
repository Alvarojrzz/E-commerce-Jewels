document.addEventListener('DOMContentLoaded', function () {
    const calendarEl   = document.getElementById('calendar');
    const modal        = document.getElementById('eventModal');
    const closeModal   = document.querySelector('.close-modal');
    const eventTitle   = document.getElementById('eventTitle');
    const eventDate    = document.getElementById('eventDate');
    const eventTime    = document.getElementById('eventTime');
    const eventDescription = document.getElementById('eventDescription');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        events: '/web/pages/events.php',
        eventClick: function(info) {
            const props = info.event.extendedProps;
            const date = info.event.start;
            const formattedDate = date.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            eventTitle.textContent       = info.event.title;
            eventDate.textContent        = formattedDate;
            eventTime.textContent        = props.time;
            eventDescription.textContent = props.description;
            modal.style.display = 'block';
        }
    });

    calendar.render();

    closeModal.onclick = () => modal.style.display = 'none';
    window.onclick = e => { if (e.target === modal) modal.style.display = 'none'; };

    fetch('/web/pages/events.php')
        .then(r => r.json())
        .then(events => {
            const eventListEl = document.getElementById('event-list');
            const listTitle   = document.createElement('h3');
            listTitle.textContent = 'Próximos eventos';
            eventListEl.appendChild(listTitle);

            const today = new Date();
            today.setHours(0,0,0,0);

            const upcoming = events
                .filter(ev => {
                const startDate = new Date(ev.start);
                startDate.setHours(0,0,0,0);
                return startDate >= today;
                })
                .sort((a, b) => new Date(a.start) - new Date(b.start));

            if (upcoming.length === 0) {
                const msg = document.createElement('p');
                msg.textContent = 'No hay próximos eventos.';
                eventListEl.appendChild(msg);
                return;
            }

            upcoming.forEach(ev => {
                const date = new Date(ev.start);
                const formatted = date.toLocaleDateString('es-ES', {
                day: 'numeric', month: 'short', year: 'numeric'
                });
                const div = document.createElement('div');
                div.className = 'event-item';
                div.innerHTML = `
                <h4>${ev.title}</h4>
                <p>${formatted}</p>
                <p>${ev.extendedProps.time}</p>
                `;
                eventListEl.appendChild(div);
            });
        })
        .catch(err => console.error('Error cargando eventos:', err));
    });