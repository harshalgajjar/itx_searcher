<?php
  $link=$_GET['link'];
  error_reporting(0);

  $content=file_get_contents($link);
  $filename="temp_content" . rand();
  file_put_contents($filename.'.itx',$content);

  $shell_command0='itrans -I ' . $filename . '.itx -o ' . $filename . '.tex';
  $shell_output0=shell_exec($shell_command0 . ' 2>&1');

  $shell_command1='pdflatex ' . $filename . '.tex';
  $shell_output1=shell_exec($shell_command1 . ' 2>&1');

  $shell_command2='mv ' . $filename . '.pdf ./pdf/' . $filename . '.pdf';
  $shell_output2=shell_exec($shell_command2 . ' 2>&1');

  $pdfURL='./pdf' . $filename . '.pdf';

  //header('Location: ' . $pdfURL);

  unlink($filename . '.itx');
  unlink($filename . '.tex');
?>

<html>
<head>
  <style>
  code{
    background-color:rgb(100,100,100);
    color:rgb(300,300,300);
    display: inline-block;
    padding:5px;
    border-radius: 2px;
    margin-top:5px;
  }
  </style>
</head>
<body>


<button onclick="window.location.href='<?php echo './pdf' . $filename . '.pdf'; ?>'">Download PDF</button><br /><br />

NOTE:
Appropriate packages to execute the following commands must be installed on server for this script to function:
<ol>
  <li>
    <code>itrans</code> (<a href="https://ctan.org/pkg/itrans-processor" target="_blank">https://ctan.org/pkg/itrans-processor</a>)
  </li>
  <li>
    <code>pdflatex</code> (<a href="https://ctan.org/pkg/tex" target="_blank">https://ctan.org/pkg/tex</a>)
  </li>
</ol>

  Following commands were executed on server:
  <ol>
    <li>
      <code><?php echo $shell_command0; ?></code><br />
      Output: <code><?php echo $shell_output0; ?></code>
    </li>
    <li>
      <code><?php echo $shell_command1; ?></code><br />
      Output: <code><?php echo $shell_output1; ?></code>
    </li>
    <li>
      <code><?php echo $shell_command2; ?></code><br />
      Output: <code><?php echo $shell_output2; ?></code>
    </li>
  </ol>

  Then the following files were deleted:

  <ol>
    <li>
      <?php echo $filename . '.itx'; ?>
    </li>
    <li>
      <?php echo $filename . '.tex'; ?>
    </li>
  </ol>

  To leave the following file(s) if ever created:

  <ol>
    <li>
      <?php echo './pdf' . $filename . '.pdf'; ?>
    </li>
  </ol>

</body>
