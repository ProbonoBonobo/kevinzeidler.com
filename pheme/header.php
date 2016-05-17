
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<link rel="stylesheet" href="http://potemkinvillage.local/vagrant/public/css/unittests.css">
<head>
    <script src="http://www.kevinzeidler.com/js/jquery.js"></script>
    <meta charset="UTF-8">
    <title>UNIT TESTS</title>
    <h1>Pheme.php: Proof of correctness</h1>
    <p>Pheme (an elision of "PHP" and "Scheme") is an intelligent, non-blocking access control module designed to
        prevent unwanted inputs from being extended to the client. It functions much in the same way that unit tests do,
        but differs in that the tests are tightly integrated with the logic of the program itself. Rather than reporting
        errors, it takes note of them. In the event of critical errors, it notifies me. A possible use case is when there
        might exist multiple "paths" to success, and there is some runtime uncertainty w/r/t which one happens to be correct at runtime. In such cases,
        we may not wish to defer the execution of unit tests until the program is finished; rather, we might wish to try a few
        different possibilities and discard bad inputs as they come in. When a potential input satisfies what we're
        looking for, it would be nice if the thread returned it to the namespace immediately so we don't keep
        searching.</p>
    <p>For a visual synopsis of how it works, I recommend checking out this <a href="http://gregor-samsa.herokuapp.com">register machine</a>
        I made a few months ago (I drew particular inspiration from the way it handles chained arithmetic).</p>
    <div id="root-testing-header">

        <table id="root-testing-container">
            <h2>Here's a bunch of tests.</h2>
            <code>Here's some dangerous, highly executable code.</code>

            <tr>
                <td>#</td>
                <td>Challenge</td>
                <td>Run</td>
                <td>Should output</td>
                <td>My output</td>
                <td>P/F?</td>
            </tr>


