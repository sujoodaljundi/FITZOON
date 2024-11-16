// Using Bootstrap modal events
document.addEventListener("DOMContentLoaded", function() {
    // Listen for all modal events
    document.querySelectorAll(".modal").forEach(modal => {
      modal.addEventListener("show.bs.modal", function(event) {
        // Get the modal element and find the specific country select within this modal
        const countrySelect = modal.querySelector("select[name='country']");
        
        if (countrySelect) {
          fetchCountries(countrySelect); // Fetch countries when the modal is about to be shown
        }
      });
    });
  
    function fetchCountries(countrySelect) {
     fetch("https://restcountries.com/v3.1/all")
        .then((response) => {
          if (!response.ok) throw new Error("Network response was not ok");
          console.log(response.json());
          return response.json();
        })
        .then((data) => {
          countrySelect.innerHTML = "<option value='' disabled selected>Select your country</option>"; // Reset the select
          data.forEach((country) => {
            const option = document.createElement("option");
            option.value = country.name.common;
            option.textContent = country.name.common;
            countrySelect.appendChild(option);
          });
        })
        .catch((error) => console.error("Error fetching countries:", error));
    }
  });
  