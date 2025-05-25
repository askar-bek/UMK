<?php
include 'thematicPlanBar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once "db.php";
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$username = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Course Plan</title>
  <link rel="stylesheet" href="thematicPlan.css">
</head>
<body>
<h2>Course Plan and Competencies (by semesters)</h2>
<p>It is recommended to use a modular structure for the course syllabus, where each module is considered as a component (section) of the subject, followed by an interim evaluation. The syllabus is developed strictly in accordance with the types of activities and the hourly norms established in the working academic plan (workload in academic hours).
</p>

  <form id="thematicForm" onsubmit="return false;">
    <table id="thematicTable">
      <thead>
        <tr>
          <th rowspan="2">№</th>
          <th rowspan="2">Name of Sections and Topics of the Course <br>(Lectures and Seminars/Practical Classes)</th>
          <th colspan="4">Classroom Activities</th>
          <th rowspan="2">Total Hours for Classroom Work</th>
          <th rowspan="2">Self-Study and Practical Tasks</th>
          <th rowspan="2">Student's Independent Work</th>
          <th rowspan="2">Competencies Developed</th>
          <th rowspan="2">Educational Technologies, Methods, and Teaching Approaches Used</th>
          <th rowspan="2">Forms of Ongoing and Interim Performance Evaluation</th>
          <th rowspan="2">Delete</th>
        </tr>
        <tr>
          <th>Lectures</th>
          <th>Seminars</th>
          <th>Practical Classes</th>
          <th>Laboratory Work</th>
        </tr>
        <tr>
          <td class="center-number">1</td>
          <td class="center-number">2</td>
          <td class="center-number">3</td>
          <td class="center-number">4</td>
          <td class="center-number">5</td>
          <td class="center-number">6</td>
          <td class="center-number">7</td>
          <td class="center-number">8</td>
          <td class="center-number">9</td>
          <td class="center-number">10</td>
          <td class="center-number">11</td>
          <td class="center-number">12</td>
          <td class="center-number">13</td>
        </tr>
      </thead>
      <tbody id="thematicTbody">
        <!-- Rows will be inserted here -->
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><b>Total Hours:</b></td>
          <td id="sum-lect"></td>
          <td id="sum-semin"></td>
          <td id="sum-pract"></td>
          <td id="sum-lab"></td>
          <td id="sum-total-aud"></td>
          <td id="sum-srsp"></td>
          <td id="sum-self"></td>
          <td colspan="3"></td>
          <td></td>
        </tr>
      </tfoot>
    </table>
    <button type="button" class="add-row-btn" onclick="addRow()">Add Row</button>
  </form>
  
  <button id="save-table-btn" type="button" onclick="saveTable()">Save Table</button>
  <span id="save-status"></span><br><br>
  
<script>
let rowCount = 0;
const fields = [
  'theme',     // 1 Name of Sections and Topics
  'lect',      // 2 Lectures
  'semin',     // 3 Seminars
  'pract',     // 4 Practical Classes
  'lab',       // 5 Laboratory Work
  'total_aud', // 6 Total Hours for Classroom Work
  'srsp',      // 7 Self-Study and Practical Tasks
  'self',      // 8 Student's Independent Work
  'competence',// 9 Competencies Developed
  'tech',      // 10 Educational Technologies...
  'control'    // 11 Forms of Ongoing...
];

// Add a row to the table
function addRow(rowData = {}) {
  const tbody = document.getElementById('thematicTbody');
  const tr = document.createElement('tr');
  rowCount++;

  // №
  let td = document.createElement('td');
  td.textContent = tbody.children.length + 1;
  tr.appendChild(td);

  // Theme (textarea)
  td = document.createElement('td');
  let input = document.createElement('textarea');
  input.value = rowData.theme || "";
  input.name = 'theme';
  td.appendChild(input);
  tr.appendChild(td);

  // Other fields
  for (let i = 1; i < fields.length; i++) {
    td = document.createElement('td');
    // Use input type=number for numeric fields, textarea for others
    if (['lect','semin','pract','lab','total_aud','srsp','self'].includes(fields[i])) {
      input = document.createElement('input');
      input.type = 'number';
    } else {
      input = document.createElement('textarea');
    }
    input.value = rowData[fields[i]] || "";
    input.name = fields[i];
    td.appendChild(input);
    tr.appendChild(td);
  }

  // Delete button
  td = document.createElement('td');
  const delBtn = document.createElement('button');
  delBtn.type = "button";
  delBtn.textContent = "Delete";
  delBtn.onclick = function () {
    tr.remove();
    updateRowNumbers();
    updateSums();
  }
  td.appendChild(delBtn);
  tr.appendChild(td);

  tbody.appendChild(tr);
  updateRowNumbers();
  updateSums();
}

// Update row numbers
function updateRowNumbers() {
  const rows = document.querySelectorAll('#thematicTbody tr');
  rows.forEach((tr, i) => {
    tr.children[0].textContent = i + 1;
  });
}

// Update sum fields
function updateSums() {
  const sumFields = ['lect', 'semin', 'pract', 'lab', 'total_aud', 'srsp', 'self'];
  const sums = {};
  sumFields.forEach(f => sums[f] = 0);

  document.querySelectorAll('#thematicTbody tr').forEach(tr => {
    sumFields.forEach((field, i) => {
      const input = tr.querySelector(`[name="${field}"]`);
      if (input && input.value) sums[field] += parseInt(input.value) || 0;
    });
  });
  sumFields.forEach(f => {
    document.getElementById('sum-' + f.replace(/_/g, '-')).textContent = sums[f] || '';
  });
}

// Save table via AJAX
function saveTable() {
  const rows = [];
  document.querySelectorAll('#thematicTbody tr').forEach(tr => {
    const row = {};
    fields.forEach(field => {
      const input = tr.querySelector(`[name="${field}"]`);
      row[field] = input ? input.value : "";
    });
    rows.push(row);
  });

  fetch('thematicPlanSave.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({rows})
  })
  .then(resp => resp.text())
  .then(msg => {
    document.getElementById('save-status').textContent = msg;
  })
  .catch(() => {
    document.getElementById('save-status').textContent = 'Save error!';
  });
}

// Update sums when table changes
document.addEventListener('input', function (e) {
  if (['INPUT', 'TEXTAREA'].includes(e.target.tagName)) updateSums();
});

// Load existing rows via AJAX on page load
window.addEventListener('DOMContentLoaded', function() {
  fetch('thematicPlanSave.php')
    .then(resp => resp.json())
    .then(data => {
      (data.rows || []).forEach(row => addRow(row));
    })
    .catch(() => {
      // If nothing loaded, add a blank row as a starting point
      if (document.getElementById('thematicTbody').children.length === 0) addRow();
    });
});
</script>
</body>
</html>