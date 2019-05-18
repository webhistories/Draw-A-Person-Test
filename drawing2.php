    <?php
// Start or resume session
session_start(); 

// Extend cookie life time by an amount of your liking
$cookieLifetime = 365 * 24 * 60 * 60; // A year in seconds
setcookie(session_name(),session_id(),time()+$cookieLifetime);

  ?>
  
  <!DOCTYPE html>
  <html lang="zxx">
  <head>
    <!-- Mobile Specific Meta -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="Colorlib">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>DAP - Drawing Area - 2.222222</title>
 <!-- iNoBounce to prevent bouncing -->
  <link href="$1%20Recognizer_files/styles.css" rel="stylesheet" type="text/css">
  <!--[if IE]><script src="excanvas.js"></script><![endif]-->
  <script type="text/javascript" src="$1%20Recognizer_files/canvas.js"></script>
  <script type="text/javascript" src="$1%20Recognizer_files/gentilis-normal-normal.js"></script>
  <script type="text/javascript" src="$1%20Recognizer_files/dollar.js"></script>
  <script type="text/javascript" src="$1%20Recognizer_files/shortstraw.js"></script>
  <script type="text/javascript" src="sample.js"></script>
  <script type="text/javascript"><!--
    //
    // Startup
    //
    var _isDown, _points, _strokes, _r, _g, _rc; // global variables
    function onLoadEvent()
    {
      _points = new Array(); // point array for current stroke
      _strokes = new Array(); // array of point arrays
      _r = new NDollarRecognizer(document.getElementById('useBoundedRotationInvariance').checked);

      var canvas = document.getElementById('myCanvas');
      _g = canvas.getContext('2d');
      _g.lineWidth = 3;
      _g.font = "16px Gentilis";
      _rc = getCanvasRect(canvas); // canvas rect on page
      _g.fillStyle = "rgb(255,255,136)";
      _g.fillRect(0, 0, _rc.width, 20);

      _isDown = false;
    }
    function getCanvasRect(canvas)
    {
      var w = canvas.width;
      var h = canvas.height;

      var cx = canvas.offsetLeft;
      var cy = canvas.offsetTop;
      while (canvas.offsetParent != null)
      {
        canvas = canvas.offsetParent;
        cx += canvas.offsetLeft;
        cy += canvas.offsetTop;
      }
      return {x: cx, y: cy, width: w, height: h};
    }
    function getScrollX()
    {
      var scrollX = $(window).scrollLeft();
      return scrollX;
    }
    function getScrollY()
    {
      var scrollY = $(window).scrollTop();
      return scrollY;
    }
    //
    // Checkbox option for using limited rotation invariance requires rebuilding the recognizer.
    //
    function confirmRebuild()
    {
      if (confirm("Changing this option will discard any user-defined gestures you may have made."))
      {
        _r = new NDollarRecognizer(document.getElementById('useBoundedRotationInvariance').checked);
      }
      else
      {
        var chk = document.getElementById('useBoundedRotationInvariance');
        chk.checked = !chk.checked; // undo click
      }
    }
    //
    // Mouse Events
    //
    function touchStartEvent(x, y, button)
    {
      document.onselectstart = function() { return false; } // disable drag-select
      document.ontouchstart = function() { return false; } // disable drag-select
      if (button <= 1)
      {
        _isDown = true;
        x -= _rc.x - getScrollX();
        y -= _rc.y - getScrollY();
        if (_points.length == 0)
        {
          _strokes.length = 0;
          _g.clearRect(0, 0, _rc.width, _rc.height);
        }
        _points.length = 1; // clear
        _points[0] = new Point(x, y);
        drawText("Recording stroke #" + (_strokes.length + 1) + "...");
        var clr = "rgb(" + rand(0,200) + "," + rand(0,200) + "," + rand(0,200) + ")";
        _g.strokeStyle = clr;
        _g.fillStyle = clr;
        _g.fillRect(x - 4, y - 3, 9, 9);
      }
      /**else if (button == 2)
      {
        drawText("Recognizing gesture...");
      }**/
    }
    function touchMoveEvent(x, y, button)
    {
      if (_isDown)
      {
        x -= _rc.x - getScrollX();
        y -= _rc.y - getScrollY();
        _points[_points.length] = new Point(x, y); // append
        drawConnectedPoint(_points.length - 2, _points.length - 1);
      }
    }
    function touchEndEvent(x, y, button)
    {
      document.onselectstart = function() { return true; } // enable drag-select
      document.ontouchstart = function() { return true; } // enable drag-select
      if (button <= 1)
      {
        if (_isDown)
        {
          _isDown = false;
          _strokes[_strokes.length] = _points.slice(); // add new copy to set
          drawText("Stroke #" + _strokes.length + " recorded.");
        }
      }
     /** else if (button == 2) // segmentation with right-click
      {
        if (_strokes.length > 1 || (_strokes.length == 1 && _strokes[0].length >= 10))
        {

        //scoring
        var score = 0;
          var result = _r.Recognize(
            _strokes,
            document.getElementById('useBoundedRotationInvariance').checked,
            document.getElementById('requireSameNoOfStrokes').checked,
            document.getElementById('useProtractor').checked
            );

          if(result.Name=="head"){
            score = 1;

          }
          drawText("Result: " + result.Name + " (" + round(result.Score,2) + ") in " + result.Time + " ms. Score: "+score+" ");
        }
        else
        {
          drawText("Too little input made. Please try again.");
        }
        _points.length = 0; // clear and signal to clear strokes on next mousedown
      }**/
    }
    function drawConnectedPoint(from, to)
    {
      _g.beginPath();
      _g.moveTo(_points[from].X, _points[from].Y);
      _g.lineTo(_points[to].X, _points[to].Y);
      _g.closePath();
      _g.stroke();
    }
    function drawText(str)
    {
      _g.fillStyle = "rgb(255,255,136)";
      _g.fillRect(0, 0, _rc.width, 20);
      _g.fillStyle = "rgb(0,0,255)";
      _g.fillText(str, 1, 14);
    }
    function rand(low, high)
    {
      return Math.floor((high - low + 1) * Math.random()) + low;
    }
    function round(n, d) // round 'n' to 'd' decimals
    {
      d = Math.pow(10, d);
      return Math.round(n * d) / d;
    }
    //
    // Multistroke Adding and Clearing
    //
    function onClickAddExisting()
    {
      if (_strokes.length > 0)
      {
        if (_strokes.length < 5 || confirm("With " + _strokes.length + " component strokes, it will take a few moments to add this gesture. Proceed?"))
        {
          var multistrokes = document.getElementById('multistrokes');
          var name = multistrokes[multistrokes.selectedIndex].value;
          var num = _r.AddGesture(name, document.getElementById('useBoundedRotationInvariance').checked, _strokes);


          drawText("\"" + name + "\" added. No. of \"" + name + "\" defined: " + _strokes + ".");
          _points.length = 0; // clear and signal to clear strokes on next mousedown
        }
      }
    }
    function onClickAddCustom()
    {
      var name = document.getElementById('custom').value;
      if (_strokes.length > 0 && name.length > 0)
      {
        if (_strokes.length < 5 || confirm("With " + _strokes.length + " component strokes, it will take a few moments to add this gesture. Proceed?"))
        {
          var num = _r.AddGesture(name, document.getElementById('useBoundedRotationInvariance').checked, _strokes);
          console.log("HELLO");
          drawText("\"" + name + "\" added. No. of \"" + name + "\" defined: " + num + ".");
          _points.length = 0; // clear and signal to clear strokes on next mousedown
        }
      }
    }
    function onClickCustom()
    {
      document.getElementById('custom').select();
    }
    function onClickDelete()
    {
      var num = _r.DeleteUserGestures(); // deletes any user-defined multistrokes
      alert("All user-defined gestures have been deleted. Only the 1 predefined gesture remains for each of the " + num + " types.");
      _points.length = 0; // clear and signal to clear strokes on next mousedown
    }
    function onClickClearStrokes()
    {
      _points.length = 0; // clear and signal to clear strokes on next mousedown
      _g.clearRect(0, 0, _rc.width, _rc.height);
      drawText("Canvas cleared.");
    }

        function onClickResult()
    {
     if (_strokes.length > 1 || (_strokes.length == 1 && _strokes[0].length >= 10))
        {

        //scoring
        var score = 0;
          var result = _r.Recognize(
            _strokes,
            document.getElementById('useBoundedRotationInvariance').checked,
            document.getElementById('requireSameNoOfStrokes').checked,
            document.getElementById('useProtractor').checked
            );

          if(result.Name=="head"){
            score = 1;

          }
          drawText("Result: " + result.Name + " (" + round(result.Score,2) + ") in " + result.Time + " ms. Score: "+score+" ");
        }
        else
        {
          drawText("Too little input made. Please try again.");
        }
        _points.length = 0; // clear and signal to clear strokes on next mousedown
    }

  // -->
  </script>

  <style>
<style>
    .header {
        height: 50px;
        position: fixed;
        left: 0;
        width: 100%;
        top: 0;
        background-color: #eee;
        text-align: center;
        line-height: 50px;
        z-index: 100;
    }

    .container {
        height: 500px;
        overflow: auto;
        -webkit-overflow-scrolling: touch;
    }

    html,body {
        margin: 0 0;
        height: 100%;
        width: 100%;
        overflow: auto;
    }

    .inner {
        -webkit-background-size: 50px 50px;
        -moz-background-size: 50px 50px;
        background-size: 50px 50px;
        background-color: #00cccc;
        background-image: -webkit-gradient(linear, 0 0, 0 100%, color-stop(.5, #66eeee), color-stop(.5, transparent), to(transparent));
        background-image: -moz-linear-gradient(#66eeee 50%, transparent 50%, transparent);
        background-image: -o-linear-gradient(#66eeee 50%, transparent 50%, transparent);
        background-image: linear-gradient(#66eeee 50%, transparent 50%, transparent);
        height: 200px;
    }
</style>

</head>
<body onload="onLoadEvent()">
<div class="header">
      <!-- Gesture image and canvas -->
      <table border="0" cellspacing="10">
        <tr>
          <td valign="top">
            <img src="multistrokes.gif"><br/>
            <p>
              <form style="font-size:10pt">
                <input type="radio" name="search" id="useGSS" checked>
                  <span style="font-weight:bold">Use Golden Section Search <i>(original)</i></span>
                </input><br/>
                <input type="radio" name="search" id="useProtractor">
                  <span style="font-weight:bold">Use Protractor <i>(faster)</i></span>
                </input><br/>
                <input type="checkbox" id="useBoundedRotationInvariance" onclick="confirmRebuild()">
                  <span style="font-weight:bold">Use bounded rotation invariance (&plusmn;45&deg;)</span>
                </input><br/>
                <input type="checkbox" id="requireSameNoOfStrokes">
                  <span style="font-weight:bold">Require same no. of component strokes</span>
                </input>
              </form>
            </p>
          </td>
          <td valign="top" align="left">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td valign="bottom">
                  <p style="font-size:10pt"><i>Make strokes on this canvas.
                  <b><u>Right-click</u> the canvas to recognize.</b>
                  If a misrecognition occurs, add the mis- recognized gesture
                  as an example of the intended gesture.</i>
                  </p>
                </td>
                <td valign="middle"><input type="button" style="width:64px;float:right" value=" Clear  " onclick="onClickClearStrokes()"></td>
              </tr>
            </table>

            <canvas id="myCanvas" width="420" height="400" style="background-color:#dddddd"
                ontouchstart="touchStartEvent(event.clientX, event.clientY, event.button)"
                ontouchmove="touchMoveEvent(event.clientX, event.clientY, event.button)"
                ontouchend="touchEndEvent(event.clientX, event.clientY, event.button)"
                oncontextmenu="return false;"> 
              <span style="background-color:#ffff88;">The &lt;canvas&gt; element is not supported by this browser.</span>
            </canvas>

            <!-- Editing area below stroking canvas area -->
            <table border="0" width="420" style="font-size:10pt">
              <tr>
                <td valign="top" align="left">Add as example of existing type:</td>
                <td valign="top" align="right">
                  <select id="multistrokes" style="width:136px" onkeypress="if (event.keyCode == 13) onClickAddExisting()">
                    <option selected value="T">T</option>
                    <option value="N">N</option>
                    <option value="D">D</option>
                    <option value="P">P</option>
                    <option value="X">X</option>
                    <option value="H">H</option>
                    <option value="I">I</option>
                    <option value="exclamation">exclamation</option>
                    <option value="line">line</option>
                    <option value="five-point star">five-point star</option>
                    <option value="null">null</option>
                    <option value="arrowhead">arrowhead</option>
                    <option value="pitchfork">pitchfork</option>
                    <option value="six-point star">six-point star</option>
                    <option value="asterisk">asterisk</option>
                    <option value="half-note">half-note</option>
                  </select>
                </td>
                <td valign="top" align="right"><input type="button" style="width:64px" value="  Add   " onclick="onClickAddExisting()" /></td>
              </tr>
              <tr>
                <td valign="top" align="left">Add as example of custom type:</td>
                <td valign="top" align="right"><input type="text" id="custom" style="width:130px" value="Type name here..." onclick="onClickCustom()" onkeypress="if (event.keyCode == 13) onClickAddCustom()" /></td>
                <td valign="top" align="right"><input type="button" style="width:64px" value="  Add   " onclick="onClickAddCustom()" /></td>
              </tr>
              <tr>
                <td valign="top" align="left">Delete all user-defined gestures:</td>
                <td valign="top" align="right">&nbsp;</td>
                <td valign="top" align="right"><input type="button" style="width:64px" value="Delete" onclick="onClickDelete()" /></td>
              </tr>
             <input type="button" style="width:64px" value="  Recognize   " onclick="drawText('Recognizing gesture...')" />

<input type="button" style="width:64px" value="  Result   " onclick="onClickResult()" />
             

            </table>
            <!-- End of editing area below stroking canvas area -->
          </td>
        </tr>
      </table>
    </p>



  </div>

    </body>
</html>