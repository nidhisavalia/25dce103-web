
const api = "api.php";

function loadEvents() {
    fetch(api)
        .then(response => response.json())
        .then(events => {
            let rows = "";

            events.forEach(event => {
                rows += `<tr>
                    <td>${event.id}</td>
                    <td>${escapeHTML(event.event_name)}</td>
                    <td>${event.event_date}</td>
                    <td>${escapeHTML(event.description || "")}</td>
                    <td>
                        <button onclick="editEvent(${event.id})">Edit</button>
                        <button onclick="deleteEvent(${event.id})">Delete</button>
                    </td>
                </tr>`;
            });

            document.getElementById("eventList").innerHTML = rows;
        });
}

function escapeHTML(value) {
    return String(value).replace(/[&<>"']/g, char => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;"
    })[char]);
}

document.getElementById("eventForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const id = document.getElementById("id").value;

    const event = {
        event_name: document.getElementById("event_name").value,
        event_date: document.getElementById("event_date").value,
        description: document.getElementById("description").value
    };

    if (id) {
        event.id = Number(id);
    }

    fetch(api, {
        method: id ? "PUT" : "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(event)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || "Request failed");
        return data;
    })
    .then(data => {
        alert(data.message);
        resetForm();
        loadEvents();
    })
    .catch(error => alert(error.message));
});

function editEvent(id) {
    fetch(api)
        .then(response => response.json())
        .then(events => {
            const event = events.find(item => Number(item.id) === id);

            if (!event) {
                alert("Event not found");
                return;
            }

            document.getElementById("id").value = event.id;
            document.getElementById("event_name").value = event.event_name;
            document.getElementById("event_date").value = event.event_date;
            document.getElementById("description").value = event.description || "";
        });
}

function deleteEvent(id) {
    if (!confirm("Are you sure you want to delete this event?")) return;

    fetch(api, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: id })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || "Request failed");
        return data;
    })
    .then(data => {
        alert(data.message);
        loadEvents();
    })
    .catch(error => alert(error.message));
}

function resetForm() {
    document.getElementById("eventForm").reset();
    document.getElementById("id").value = "";
}

loadEvents();
