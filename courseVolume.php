<?php
// Database connection settings

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and collect POST data
    $form_type = $_POST['form_type'];
    $plan_year = $_POST['plan_year'];
    $semester1 = $_POST['semester1'];
    $semester2 = $_POST['semester2'];
    $total_hours = $_POST['total_hours'];
    $total_credits = $_POST['total_credits'];
    $overall_work1 = $_POST['overall_work1'];
    $overall_work2 = $_POST['overall_work2'];
    $overall_work_total_hours = $_POST['overall_work_total_hours'];
    $overall_work_total_credits = $_POST['overall_work_total_credits'];
    $aud_work1 = $_POST['aud_work1'];
    $aud_work2 = $_POST['aud_work2'];
    $aud_work_total_hours = $_POST['aud_work_total_hours'];
    $aud_work_total_credits = $_POST['aud_work_total_credits'];
    $lecture1 = $_POST['lecture1'];
    $lecture2 = $_POST['lecture2'];
    $lecture_total_hours = $_POST['lecture_total_hours'];
    $lecture_total_credits = $_POST['lecture_total_credits'];
    $practice1 = $_POST['practice1'];
    $practice2 = $_POST['practice2'];
    $practice_total_hours = $_POST['practice_total_hours'];
    $practice_total_credits = $_POST['practice_total_credits'];
    $seminar1 = $_POST['seminar1'];
    $seminar2 = $_POST['seminar2'];
    $seminar_total_hours = $_POST['seminar_total_hours'];
    $seminar_total_credits = $_POST['seminar_total_credits'];
    $lab1 = $_POST['lab1'];
    $lab2 = $_POST['lab2'];
    $lab_total_hours = $_POST['lab_total_hours'];
    $lab_total_credits = $_POST['lab_total_credits'];
    $srs1 = $_POST['srs1'];
    $srs2 = $_POST['srs2'];
    $srs_total_hours = $_POST['srs_total_hours'];
    $srs_total_credits = $_POST['srs_total_credits'];
    $srsp1 = $_POST['srsp1'];
    $srsp2 = $_POST['srsp2'];
    $srsp_total_hours = $_POST['srsp_total_hours'];
    $srsp_total_credits = $_POST['srsp_total_credits'];
    $control1 = $_POST['control1'];
    $control2 = $_POST['control2'];
    $control_total_hours = $_POST['control_total_hours'];
    $control_total_credits = $_POST['control_total_credits'];
    $final_control1 = $_POST['final_control1'];
    $final_control2 = $_POST['final_control2'];
    $instructions = $_POST['instructions'];

    $stmt = $conn->prepare("INSERT INTO discipline_volume 
        (form_type, plan_year, semester1, semester2, total_hours, total_credits, 
        overall_work1, overall_work2, overall_work_total_hours, overall_work_total_credits,
        aud_work1, aud_work2, aud_work_total_hours, aud_work_total_credits,
        lecture1, lecture2, lecture_total_hours, lecture_total_credits,
        practice1, practice2, practice_total_hours, practice_total_credits,
        seminar1, seminar2, seminar_total_hours, seminar_total_credits,
        lab1, lab2, lab_total_hours, lab_total_credits,
        srs1, srs2, srs_total_hours, srs_total_credits,
        srsp1, srsp2, srsp_total_hours, srsp_total_credits,
        control1, control2, control_total_hours, control_total_credits,
        final_control1, final_control2, instructions) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssssssssssssssssssssssssssssssssssssssssssss", 
        $form_type, $plan_year, $semester1, $semester2, $total_hours, $total_credits, 
        $overall_work1, $overall_work2, $overall_work_total_hours, $overall_work_total_credits,
        $aud_work1, $aud_work2, $aud_work_total_hours, $aud_work_total_credits,
        $lecture1, $lecture2, $lecture_total_hours, $lecture_total_credits,
        $practice1, $practice2, $practice_total_hours, $practice_total_credits,
        $seminar1, $seminar2, $seminar_total_hours, $seminar_total_credits,
        $lab1, $lab2, $lab_total_hours, $lab_total_credits,
        $srs1, $srs2, $srs_total_hours, $srs_total_credits,
        $srsp1, $srsp2, $srsp_total_hours, $srsp_total_credits,
        $control1, $control2, $control_total_hours, $control_total_credits,
        $final_control1, $final_control2, $instructions
    );
    if ($stmt->execute()) {
        $success = "Данные успешно сохранены.";
    } else {
        $error = "Ошибка при сохранении данных: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Объем дисциплины и виды учебной работы</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; }
        h2 { font-size: 14pt; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px;}
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: center; font-size: 14px;}
        th { background: #f0f0f0; }
        .input { width: 90px; }
        .add-table-btn { margin: 20px 0; }
        input[readonly] { background: #eee; }
        textarea { width: 100%; min-height: 50px; }
        fieldset { margin-bottom: 35px; }
        legend { font-weight: bold; font-size: 15px; }
    </style>
    <script>
    let tableCount = 1;
    function toInt(val) { return parseInt(val) || 0; }
    function toFixed(val, digits=2) { return Number(val).toFixed(digits); }

    function sumAudWork(table) {
        let lecture1 = toInt(table.querySelector('[name$="[lecture1]"]').value);
        let practice1 = toInt(table.querySelector('[name$="[practice1]"]').value);
        let seminar1 = toInt(table.querySelector('[name$="[seminar1]"]').value);
        let lab1 = toInt(table.querySelector('[name$="[lab1]"]').value);
        let aud1 = lecture1 + practice1 + seminar1 + lab1;
        table.querySelector('[name$="[aud_work1]"]').value = aud1;

        let lecture2 = toInt(table.querySelector('[name$="[lecture2]"]').value);
        let practice2 = toInt(table.querySelector('[name$="[practice2]"]').value);
        let seminar2 = toInt(table.querySelector('[name$="[seminar2]"]').value);
        let lab2 = toInt(table.querySelector('[name$="[lab2]"]').value);
        let aud2 = lecture2 + practice2 + seminar2 + lab2;
        table.querySelector('[name$="[aud_work2]"]').value = aud2;

        table.querySelector('[name$="[aud_work_total_hours]"]').value = aud1 + aud2;
    }
    function sumOverallWork(table) {
        let aud1 = toInt(table.querySelector('[name$="[aud_work1]"]').value);
        let srs1 = toInt(table.querySelector('[name$="[srs1]"]').value);
        let srsp1 = toInt(table.querySelector('[name$="[srsp1]"]').value);
        let control1 = toInt(table.querySelector('[name$="[control1]"]').value);
        let overall1 = aud1 + srs1 + srsp1 + control1;
        table.querySelector('[name$="[overall_work1]"]').value = overall1;

        let aud2 = toInt(table.querySelector('[name$="[aud_work2]"]').value);
        let srs2 = toInt(table.querySelector('[name$="[srs2]"]').value);
        let srsp2 = toInt(table.querySelector('[name$="[srsp2]"]').value);
        let control2 = toInt(table.querySelector('[name$="[control2]"]').value);
        let overall2 = aud2 + srs2 + srsp2 + control2;
        table.querySelector('[name$="[overall_work2]"]').value = overall2;

        let total = overall1 + overall2;
        table.querySelector('[name$="[overall_work_total_hours]"]').value = total;
        table.querySelector('[name$="[overall_work_total_credits]"]').value = total ? toFixed(total/30, 2) : '';
    }
    function sumRows(table) {
        const rows = [
            {col2:'overall_work1', col3:'overall_work2', total:'overall_work_total_hours'},
            {col2:'aud_work1', col3:'aud_work2', total:'aud_work_total_hours'},
            {col2:'lecture1', col3:'lecture2', total:'lecture_total_hours'},
            {col2:'practice1', col3:'practice2', total:'practice_total_hours'},
            {col2:'seminar1', col3:'seminar2', total:'seminar_total_hours'},
            {col2:'lab1', col3:'lab2', total:'lab_total_hours'},
            {col2:'srs1', col3:'srs2', total:'srs_total_hours'},
            {col2:'srsp1', col3:'srsp2', total:'srsp_total_hours'},
            {col2:'control1', col3:'control2', total:'control_total_hours'},
        ];
        rows.forEach(r => {
            let col2 = toInt(table.querySelector('[name$="['+r.col2+']"]').value);
            let col3 = toInt(table.querySelector('[name$="['+r.col3+']"]').value);
            table.querySelector('[name$="['+r.total+']"]').value = col2 + col3;
        });
    }
    function autoSumAll(table) {
        sumAudWork(table);
        sumRows(table);
        sumOverallWork(table);
        sumRows(table);
    }
    function setupTableEvents(table) {
        const fields = [
            'lecture1','practice1','seminar1','lab1',
            'lecture2','practice2','seminar2','lab2',
            'srs1','srsp1','control1','srs2','srsp2','control2'
        ];
        fields.forEach(function(name) {
            table.querySelector('[name$="['+name+']"]').addEventListener('input', function() {
                autoSumAll(table);
            });
        });
        autoSumAll(table);
    }
    function getTableHTML(idx) {
        return `
        <fieldset class="discipline-table-fieldset">
        <legend>Семестровый блок №${idx}</legend>
        <table class="semester-table">
            <tr>
                <th rowspan="2">Вид учебной работы</th>
                <th colspan="2">Семестр</th>
                <th colspan="2">Итого</th>
            </tr>
            <tr>
                <th>
                    <select name="discipline_volume[${idx}][semester1]" required>
                        ${[...Array(10)].map((_,i)=>`<option value="${i+1}">${i+1}</option>`).join('')}
                    </select>
                </th>
                <th>
                    <select name="discipline_volume[${idx}][semester2]" required>
                        ${[...Array(10)].map((_,i)=>`<option value="${i+1}">${i+1}</option>`).join('')}
                    </select>
                </th>
                <th>в часах</th>
                <th>в кредитах</th>
            </tr>
            <tr>
                <td>Общая трудоемкость</td>
                <td><input name="discipline_volume[${idx}][overall_work1]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][overall_work2]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][overall_work_total_hours]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][overall_work_total_credits]" class="input" type="number" readonly></td>
            </tr>
            <tr>
                <td>Аудиторная работа</td>
                <td><input name="discipline_volume[${idx}][aud_work1]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][aud_work2]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][aud_work_total_hours]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][aud_work_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
            </tr>
            <tr>
                <td>Лекции</td>
                <td><input name="discipline_volume[${idx}][lecture1]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][lecture2]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][lecture_total_hours]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][lecture_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
            </tr>
            <tr>
                <td>Практические занятия</td>
                <td><input name="discipline_volume[${idx}][practice1]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][practice2]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][practice_total_hours]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][practice_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
            </tr>
            <tr>
                <td>Семинары</td>
                <td><input name="discipline_volume[${idx}][seminar1]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][seminar2]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][seminar_total_hours]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][seminar_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
            </tr>
            <tr>
                <td>Лабораторные работы</td>
                <td><input name="discipline_volume[${idx}][lab1]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][lab2]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][lab_total_hours]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][lab_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
            </tr>
            <tr>
                <td>СРС</td>
                <td><input name="discipline_volume[${idx}][srs1]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][srs2]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][srs_total_hours]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][srs_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
            </tr>
            <tr>
                <td>СРСП</td>
                <td><input name="discipline_volume[${idx}][srsp1]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][srsp2]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][srsp_total_hours]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][srsp_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
            </tr>
            <tr>
                <td>Контрольные работы</td>
                <td><input name="discipline_volume[${idx}][control1]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][control2]" class="input" type="number" placeholder="ввести часы"></td>
                <td><input name="discipline_volume[${idx}][control_total_hours]" class="input" type="number" readonly></td>
                <td><input name="discipline_volume[${idx}][control_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
            </tr>
            <tr>
                <td>Вид итогового контроля</td>
                <td>
                    <select name="discipline_volume[${idx}][final_control1]">
                        <option value="Экзамен">Экзамен</option>
                        <option value="тест">тест</option>
                    </select>
                </td>
                <td>
                    <select name="discipline_volume[${idx}][final_control2]">
                        <option value="Экзамен">Экзамен</option>
                        <option value="тест">тест</option>
                    </select>
                </td>
                <td colspan="2"></td>
            </tr>
        </table>
        <button type="button" class="remove-table-btn" onclick="removeTable(this)">Удалить этот семестровый блок</button>
        </fieldset>
        `;
    }
    function addTable() {
        tableCount++;
        const wrapper = document.getElementById('tables-wrapper');
        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = getTableHTML(tableCount);
        wrapper.appendChild(tempDiv.firstElementChild);
        let lastFieldset = wrapper.querySelectorAll('.discipline-table-fieldset');
        let newTable = lastFieldset[lastFieldset.length-1].querySelector('table');
        setupTableEvents(newTable);
    }
    function removeTable(btn) {
        if(document.querySelectorAll('.discipline-table-fieldset').length > 1) {
            btn.closest('.discipline-table-fieldset').remove();
        } else {
            alert('Нельзя удалить последний блок!');
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        setupTableEvents(document.querySelector('.semester-table'));
        document.getElementById('add-table-btn').addEventListener('click', function(e){
            e.preventDefault();
            addTable();
        });
    });
    </script>
</head>
<body>
    <h2>1.3. Объем дисциплины и виды учебной работы</h2>
    <form method="post" action="discipline_volume_save.php">
        <div id="tables-wrapper">
            <!-- Первый блок таблицы -->
            <fieldset class="discipline-table-fieldset">
            <legend>Семестровый блок №1</legend>
            <table class="semester-table">
                <tr>
                    <th rowspan="2">Вид учебной работы</th>
                    <th colspan="2">Семестр</th>
                    <th colspan="2">Итого</th>
                </tr>
                <tr>
                    <th>
                        <select name="discipline_volume[1][semester1]" required>
                            <?php for ($i=1;$i<=10;$i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>
                    </th>
                    <th>
                        <select name="discipline_volume[1][semester2]" required>
                            <?php for ($i=1;$i<=10;$i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>
                    </th>
                    <th>в часах</th>
                    <th>в кредитах</th>
                </tr>
                <tr>
                    <td>Общая трудоемкость</td>
                    <td><input name="discipline_volume[1][overall_work1]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][overall_work2]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][overall_work_total_hours]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][overall_work_total_credits]" class="input" type="number" readonly></td>
                </tr>
                <tr>
                    <td>Аудиторная работа</td>
                    <td><input name="discipline_volume[1][aud_work1]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][aud_work2]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][aud_work_total_hours]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][aud_work_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
                </tr>
                <tr>
                    <td>Лекции</td>
                    <td><input name="discipline_volume[1][lecture1]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][lecture2]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][lecture_total_hours]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][lecture_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
                </tr>
                <tr>
                    <td>Практические занятия</td>
                    <td><input name="discipline_volume[1][practice1]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][practice2]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][practice_total_hours]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][practice_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
                </tr>
                <tr>
                    <td>Семинары</td>
                    <td><input name="discipline_volume[1][seminar1]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][seminar2]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][seminar_total_hours]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][seminar_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
                </tr>
                <tr>
                    <td>Лабораторные работы</td>
                    <td><input name="discipline_volume[1][lab1]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][lab2]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][lab_total_hours]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][lab_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
                </tr>
                <tr>
                    <td>СРС</td>
                    <td><input name="discipline_volume[1][srs1]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][srs2]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][srs_total_hours]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][srs_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
                </tr>
                <tr>
                    <td>СРСП</td>
                    <td><input name="discipline_volume[1][srsp1]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][srsp2]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][srsp_total_hours]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][srsp_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
                </tr>
                <tr>
                    <td>Контрольные работы</td>
                    <td><input name="discipline_volume[1][control1]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][control2]" class="input" type="number" placeholder="ввести часы"></td>
                    <td><input name="discipline_volume[1][control_total_hours]" class="input" type="number" readonly></td>
                    <td><input name="discipline_volume[1][control_total_credits]" class="input" type="number" placeholder="ввести кредиты"></td>
                </tr>
                <tr>
                    <td>Вид итогового контроля</td>
                    <td>
                        <select name="discipline_volume[1][final_control1]">
                            <option value="Экзамен">Экзамен</option>
                            <option value="тест">тест</option>
                        </select>
                    </td>
                    <td>
                        <select name="discipline_volume[1][final_control2]">
                            <option value="Экзамен">Экзамен</option>
                            <option value="тест">тест</option>
                        </select>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </table>
            <button type="button" class="remove-table-btn" onclick="removeTable(this)">Удалить этот семестровый блок</button>
            </fieldset>
        </div>
        <button type="button" id="add-table-btn" class="add-table-btn">Добавить еще таблицу для последующих семестров</button>
        <br>
        <label>Пояснения / инструкции:<br>
            <textarea name="instructions" placeholder="Введите пояснения или оставьте пустым"></textarea>
        </label>
        <br><br>
        <button type="submit">Сохранить</button>
    </form>
</body>
</html>