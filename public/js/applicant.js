// instructions accordion
let boxes = document.querySelectorAll('.box-container .box');

boxes.forEach(box => {

   let heading = box.querySelector('.heading');
   let icon = box.querySelector('.heading i');

   heading.onclick = () => {

      for (var i = 0; i < boxes.length; i++) {
         if (boxes[i] != box) {
            boxes[i].classList.remove('active');
         } else {
            box.classList.toggle('active');
         }
      }

      if (icon.classList.contains('fa-angle-down')) {
         icon.classList.replace('fa-angle-down', 'fa-chevron-right');
      } else {
         document.querySelectorAll('.box-container .box .heading .fa-angle-down').forEach(minus => {
            minus.classList.replace('fa-angle-down', 'fa-chevron-right');
         });
         icon.classList.replace('fa-chevron-right', 'fa-angle-down');
      }

   };

});

// Function to enable input field
function toggleInput() {
   var indigenousCheck = document.getElementById("indigenousCheck");
   var indigenousInput = document.getElementById("indigenousInput");
   var noCheck = document.getElementById("noCheck");

   if (indigenousCheck.checked) {
      indigenousInput.disabled = false;
      noCheck.checked = false;
   } else {
      indigenousInput.disabled = true;
   }
}

function disableInput() {
   var noCheck = document.getElementById("noCheck");
   var indigenousCheck = document.getElementById("indigenousCheck");
   var indigenousInput = document.getElementById("indigenousInput");

   if (noCheck.checked) {
      indigenousInput.disabled = true;
      indigenousCheck.checked = false;
   }
}


// Fucntion to add and remove sibling
document.getElementById('addSibling').addEventListener('click', function (event) {
   event.preventDefault();
   var originalDiv = document.querySelector('.siblingsinfo');
   var clone = originalDiv.cloneNode(true);
   clone.querySelectorAll('input, select').forEach(input => input.value = '');
   var removeButton = document.createElement('button');
   removeButton.textContent = 'Remove';
   removeButton.type = 'button';
   removeButton.classList.add('removeSibling');
   removeButton.onclick = function () { clone.remove(); };
   clone.appendChild(removeButton);
   document.getElementById('siblings-container').appendChild(clone);
});


// // Function to add a new row
// document.getElementById('addRowBtn').addEventListener('click', function (event) {
//    event.preventDefault();

//    // Get the table body
//    var tableBody = document.getElementById('familyTableBody');

//    // Create a new row
//    var newRow = document.createElement('tr');

//    // Add columns with input fields and a remove button to the row
//    newRow.innerHTML = `
//        <td><input type="text" required></td>
//        <td><input type="text" required></td>
//        <td>
//            <select required>
//                <option value="" disabled selected hidden></option>
//                <option value="F">F</option>
//                <option value="M">M</option>
//            </select>
//        </td>
//        <td><input type="date" required></td>
//        <td><input type="text" required></td>
//        <td><input type="text" required></td>
//        <td><input type="text" required></td>
//        <td><input type="text" required></td>
//        <td><input type="text" required></td>
//        <td><input type="text" required></td>
//        <td><button class="removeRowBtn">x</button></td>
//    `;

//    // Append the new row to the table body
//    tableBody.appendChild(newRow);

//    // Add event listener to the "Remove" button in the new row
//    newRow.querySelector('.removeRowBtn').addEventListener('click', function () {
//       newRow.remove(); // Remove the row
//    });
// });

// Function to toggle profile
function showprofilemenu() {
   const profileMenu = document.getElementById('profilemenu');
   if (profileMenu.style.display === 'none' || profileMenu.style.display === '') {
      profileMenu.style.display = 'block';
   } else {
      profileMenu.style.display = 'none';
   }
}

window.addEventListener('click', function (event) {
   const profileMenu = document.getElementById('profilemenu');
   const button = document.getElementById('showprofmenu');

   // Check if both elements exist before calling contains
   if (profileMenu && button && !profileMenu.contains(event.target) && !button.contains(event.target)) {
      profileMenu.style.display = 'none';
   }
});

