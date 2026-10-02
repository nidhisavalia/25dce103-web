document.addEventListener("DOMContentLoaded", function () {

    let eventContainer = document.getElementById("eventContainer");
    let searchBox = document.getElementById("searchBox");

    fetch("events.json")
        .then(response => response.json())
        .then(data => {

            displayEvents(data);

            searchBox.addEventListener("input", function () {

                let searchText = searchBox.value.toLowerCase();

                let filteredEvents = data.filter(event =>
                    event.name.toLowerCase().includes(searchText)
                );

                displayEvents(filteredEvents);

            });

        })
        .catch(error => {
            console.log("Error:", error);
            eventContainer.innerHTML = "<p>Events could not be loaded.</p>";
        });


    function displayEvents(events) {

        eventContainer.innerHTML = "";

        events.forEach(event => {

            eventContainer.innerHTML += `
                <div class="event-card">

                    <h2>${event.name}</h2>

                    <p><strong>Date:</strong> ${event.date}</p>

                    <p><strong>Venue:</strong> ${event.venue}</p>

                    <p>${event.description}</p>

                </div>
            `;

        });

    }

});