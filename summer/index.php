
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    class TableRows extends RecursiveIteratorIterator
    {
        function __construct($it)
        {
            parent::__construct($it, self::LEAVES_ONLY);
        }

        function current()
        {
            return "<td style='width: 150px; border: 1px solid black;'>" . parent::current() . "</td>";
        }

        function beginChildren()
        {
            echo "<tr>";
        }

        function endChildren()
        {
            echo "</tr>" . "\n";
        }
    }

    require_once 'pass.php';
    try {
        $conn = new PDO($dsn);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<table style='border: solid 1px black;'>";

        if ($_POST['options'] == 1) {
            $stmt = $conn->prepare("select * from abitur where gender = 'male';");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>id_abit</th><th>fio</th><th>adress</th><th>birthdate</th><th>gender</th><th>passport</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 2) {
            $stmt = $conn->prepare("select * from examinator where payment<=500 and payment>=300;");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>id_exam</th><th>fio</th><th>payment</th><th>examdate</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 3) {          
            $stmt = $conn->prepare("select * from abitur where birthdate>= '2000-01-01' and birthdate<='2004-12-31';");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>id_abit</th><th>fio</th><th>adress</th><th>birthdate</th><th>gender</th><th>passport</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 4) {
            $name = $_POST['pos_name'];
            $stmt = $conn->prepare("select * from examinator where fio = '$name';");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>id_exam</th><th>fio</th><th>payment</th><th>examdate</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 5) {
        	$l = $_POST['lowerBonus1'];
            $h = $_POST['upperBonus1'];
            $stmt = $conn->prepare("select abitur.fio as ФИОабитуриента, examinator.fio as ФИОэкзаменатора, exam.title AS названиеэкзамена, examinator.examdate AS датасдачиэкзамена, exam.grade AS оценка FROM abitur join exam ON abitur.id_abit=exam.abit_id join examinator on exam.exam_id = examinator.id_exam WHERE exam.exam_date BETWEEN '$l' AND '$h';");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>Код отношения</th><th>Код подразделения</th><th>Код должности</th><th>Базовый оклад</th><th>Бонус 2</th><th>Зарплата</th><th>Длительность отпуска</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 6) {
            $stmt = $conn->prepare("");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>Код отношения</th><th>Код должности</th><th>Название должности</th><th>Средняя зарплата</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 7) {
            $stmt = $conn->prepare("select birthdate from abitur group by birthdate;");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>birthdate</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
        else if ($_POST['options'] == 8) {
            $stmt = $conn->prepare("select title, avg(grade) as avgGrade from exam group by title;");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo "<tr><th>title</th></tr>";
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    echo "</table>";
    echo "<form method='POST' action='index.html'> <button type = 'submit'>На главную</button></form>";

}
