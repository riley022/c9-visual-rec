
// Translations for en_US



function BoxButton(x,y,height, width, label)
{
  this.x = x;
  this.y = y;
  this.height = height;
  this.width = width;
  this.label = label;
}

BoxButton.prototype.contains = function(mx, my)
{
  var contained = true;
  if(mx <= this.x || mx >= this.x + this.width)
  {
    contained = false;
  }
  if(my <= this.y || my >= this.y + this.height)
  {
    contained = false;
  }

  return contained; 
}

BoxButton.prototype.draw = function(context)
{
  context.beginPath();
  context.rect(this.x,this.y,this.width,this.height);
  context.stroke()
  context.closePath();

  if(this.label !== undefined)
  {

    var text_height = this.height * 0.8
    var text_x = (this.x + this.x + this.width) * 0.5; 
    var text_y = (this.y + this.y + this.height) * 0.5;


    console.log(this.x, this.y, this.width, this.height, text_x, text_y, text_height);

    context.font = "" + text_height +"px Arial";
    context.fillStyle="black";
    context.textAlign = "center";
    context.textBaseline = "middle";
    context.fillText(this.label, text_x, text_y);

  }
}

function AccessPoint(name, x, y, radius, status)
{
  this.name = name;
  this.x = x - 15;
  this.y = y + 8;
  this.radius = radius;
  this.status = status;
}

AccessPoint.prototype.contains = function(x, y)
{
  var square_dist = Math.pow(this.x - x, 2) + Math.pow(this.y - y,2);
  if ( square_dist <= Math.pow(this.radius + 5, 2))
  {
    console.log(this.name,"has been clicked");
    return true;
  }
  return false;
}

AccessPoint.prototype.draw = function(context)
{
  context.beginPath();
  context.arc(this.x, this.y, this.radius, 0, 2 * Math.PI);
  console.log(this.status.length);
  console.log(this.status);
  if(this.status === "Up"){
    
    context.fillStyle = "green";
  } else if (this.status ==="Down"){
    
    context.fillStyle = "red";
  } else {
    context.fillStyle = "yellow";
  }
  context.stroke();
  context.fill();
  context.closePath();
}

function determineOrientation(height, width, start_point, triangle_size, max_height, max_width)
{
  var left = true;
  var top = true;

  var start_x = start_point[0];
  var start_y = start_point[1];

  var x_right_side = start_x + triangle_size + width;

  if(max_width < x_right_side)
  {
    left = false;
  }

  var y_bottom_side = start_y + height;

  if(max_height < y_bottom_side)
  {
    top = false;
  }

  return {isTop: top, isLeft: left};
}

function MessageBox(height, width, beginning, isLeft, isTop, contents)
{
  this.height = height;
  this.width = width;
  this.beginning = beginning;
  this.coords = [];
  this.isLeft = isLeft;
  this.isTop = isTop;
  this.contents = contents;
  
  this.BorderCoordinates();
  this.ContentsCoordinates();
}

MessageBox.prototype.BorderCoordinates = function()
{
  var horiz_mult = 1;
  var vert_mult = 1;
  
  if(!this.isLeft)
    horiz_mult = -1;
  if(!this.isTop)
    vert_mult = -1;
  
  var C1C2 = horiz_mult * (15 + this.width);
  var C2C3 = vert_mult * this.height;
  var C3C4 = horiz_mult * -1 * this.width;
  var C4C5 = vert_mult * -1 * (this.height - 15);

  var C1 = this.beginning;
  var C2 = [C1[0] + C1C2, C1[1]];
  var C3 = [C2[0], C2[1] + C2C3];
  var C4 = [C3[0] + C3C4, C3[1]];
  var C5 = [C4[0], C4[1] + C4C5];

  this.coords = [C1, C2, C3, C4, C5];
}


MessageBox.prototype.ContentsCoordinates = function()
{
  var min = Number.MAX_SAFE_INTEGER;
  var min_index = 0;
  for(var i = 0; i < this.coords.length; i++)
  {
    var dis = Math.pow(this.coords[i][0],2) + Math.pow(this.coords[i][1], 2);
    if(dis < min)
    {
      min = dis; 
      min_index = i;
    }
  }

  var top_left_x = this.coords[min_index][0];
  var top_left_y = this.coords[min_index][1];
  if (top_left_x === this.beginning[0] && top_left_y === this.beginning[1])
  {
    top_left_x += 15;
  }
  this.topLeft = [top_left_x, top_left_y];

  this.headingCoordinates = [top_left_x + 5,top_left_y + 25];
  this.contentsCoordinates = [this.headingCoordinates[0] + 5, this.headingCoordinates[1] + 21];
  console.log(this.headingCoordinates);
  console.log(this.beginning);
}


MessageBox.prototype.CreateAddButton = function()
{
  var bottom_right_x = this.topLeft[0] + this.width, bottom_right_y = this.topLeft[1] + this.height;
  var add_top_x = bottom_right_x - 45, add_top_y = bottom_right_y - 25; 
  return new BoxButton(add_top_x, add_top_y, 20, 40, "Add");
}


MessageBox.prototype.draw = function(context)
{
  console.log("drawing message box");
  context.beginPath();
    for(var i = 0; i < this.coords.length; i++) {
      var x = this.coords[i][0];
      var y = this.coords[i][1];
      if (i === 0) {
        context.moveTo(x,y);
      } else {
        context.lineTo(x,y);
      }
    }
    var begin_x = this.coords[0][0], begin_y = this.coords[0][1];
    context.lineTo(begin_x, begin_y);
    context.fillStyle="white";
    context.stroke();
    context.fill();
    context.closePath();
    
    context.fillStyle = "black";
    context.textAlign = "start";
    context.textBaseline = "alphabetic";
    var current_x = this.headingCoordinates[0], current_y = this.headingCoordinates[1];
    for (var i = 0; i < this.contents.length; i++)
    {

      if( i === 0 )
      {
        context.font = "24px Arial";
        context.fillText(this.contents[0], current_x, current_y);
      } else {
        context.font = "16px Arial";
        context.fillText(this.contents[i], current_x, current_y);
      }
      current_y += 21;
    }
}


function CanvasState(canvas)
{
  this.canvas = canvas;
  this.context = canvas.getContext('2d');
  this.width = canvas.width;
  this.height = canvas.height; 
  
  var stylePaddingLeft, stylePaddingTop, styleBorderLeft, styleBorderTop;
  if( document.defaultView && document.defaultView.getComputedStyle)
  {
    this.stylePaddingLeft = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingLeft'],10) || 0;
    this.stylePaddingTop = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingTop'],10) || 0;
    this.styleBorderLeft = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderLeftWidth'],10) || 0;
    this.styleBorderTop = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderTopWidth'],10) || 0;
  }
  
  var html = document.body.parentNode;
  this.htmlTop = html.offsetTop;
  this.htmlLeft = html.offsetLeft;
  
  this.access_points = [];
  this.message_box = null;
  this.add_button = null;
  
  var state = this;
  
  canvas.addEventListener('mousedown', function(e)
  {
    var mouse = state.getMouse(e);
    var mx = mouse.x, my = mouse.y;
    
    var aps = state.access_points;
    var ap_pressed = false;
    var selected_ap;
    for (var i = 0; i < aps.length; i++)
    {
      if(aps[i].contains(mx, my))
      {
        selected_ap = aps[i];
        ap_pressed = true;
        break;
      }
    }

    if(ap_pressed) {
      var orient = determineOrientation(160, 200, [mx, my], 15, state.height, state.width);
      var isTop = orient.isTop, isLeft = orient.isLeft;
      
      var message_box_x = selected_ap.x + (isLeft ? selected_ap.radius : -select_ap.radius);
      var message_box_y = selected_ap.y;


      var heading = selected_ap.name + "-" + selected_ap.status
      var message_box_contents = [heading]
      
      
      state.message_box = new MessageBox(160,200, [message_box_x, message_box_y], isLeft,isTop,message_box_contets);
      state.add_button = state.message_box.CreateAddButton();
    } else if (state.add_button  !== null){
      if(state.add_button.contains(mx,my)){
        console.log("Added");
      }
    } else {
      state.message_box = null;
      state.add_button = null;
    }
    state.draw();
  }, true);
}

CanvasState.prototype.add = function(access_point)
{
  this.access_points.push(access_point);
}

CanvasState.prototype.draw = function()
{
  this.context.clearRect(0,0,this.width, this.height);
  var access_points = this.access_points;
  for(var i = 0; i < access_points.length; i++)
  {
    access_points[i].draw(this.context);
  }
  if (this.message_box !== null)
  {
    this.message_box.draw(this.context);
    this.add_button.draw(this.context); 
  }
}

CanvasState.prototype.getMouse = function(e)
{
  var element = this.canvas, offsetX = 0, offsetY = 0, mx, my;

  
  if(element.offSetParent !== undefined)
  {
    do
    {
      offsetX += element.offsetLeft;
      offsetY += element.offsetop;
    } while (element = element.offsetParent)
  }
  offsetX = this.stylePaddingLeft + this.styleBorderLeft + this.htmlLeft;
  offsetY = this.stylePaddingLeft + this.styleBorderTop + this.htmlTop;

  mx = e.pageX - offsetX - 30;
  my = e.pageY - offsetY - 370;

  console.log(mx,my);

  return {x: mx, y: my};
}

function createAPList(APcsv)
{
  var lines = APcsv.split('\n');
  var aps = new Array(lines.length); 
  for (var i = 0;i < lines.length; i++) 
  {
    aps[i] = lines[i].split(",");
  }
  return aps;
}


function getNonMappedAps(canvasState, status_dict)
{
  var mapped_aps = canvasState.access_points;
  var mapped = []
  for(var i = 0; i < mapped_aps.length; i++)
  {
    mapped.push( mapped_aps[i].name );
  }
  var all_aps = Object.keys(status_dict);
  var unmapped = []
  for (var i = 0; i < all_aps.length; i++)
  {
    var ap = all_aps[i];
    if (mapped.indexOf(ap) === -1)
    {
      unmapped.push(ap);
    }
  }
  return unmapped;
}

function getAPcellString(ap, stat)
{
  var cellString = "<td>"+ap+"</td>";
  if( stat === "Up" )
  {
    cellString += '<td style="color:green">Up</td>';
  } else {
    cellString += '<td style="color:red">Down</td>';
  }
  return cellString;
}

function makeUnmapString(unmapped, status_dict)
{
  var string = "";
  var col_length = Math.ceil(unmapped.length / 2);
  for (var i = 0; i < col_length; i++)
  {
    var ap1 = unmapped[i];
    var ap1String = getAPcellString(ap1, status_dict[ap1]);
    var ap2String;
    if( i + col_length < unmapped.length)
    {
      ap2 = unmapped[i + col_length];
      ap2String = getAPcellString(ap2, status_dict[ap2]);
    } else {
      break;
    }
    string += "<tr>"+ap1String + ap2String + "</tr>";
  }
  return string;
}

function loadAPData(canvasState, heightRatio, widthRatio)
{
  var xhttp = new XMLHttpRequest();
  var token = "0001"
  console.log("AJAX CALL STARTING");
  xhttp.onreadystatechange = function()
  {
   
    if (this.readyState == 4 && this.status == 200) 
    {
      console.log("AP Loading");
      var APList = createAPList(this.responseText);
      for (var i = 0; i < APList.length - 1; i++)
      {
        var name = APList[i][0];
        var x = parseInt(APList[i][1]);
        var  y = parseInt(APList[i][2]);
        var status = APList[i][3]
        if(widthRatio !== 1)
        {
          x = x * widthRatio;
        }
        if (heightRatio !== 1)
        {
          y = y * heightRatio;
        }
        var AP = new AccessPoint(name, x, y, 13,status.slice(0,status.length - 1));
        canvasState.add(AP);      
      } 
      canvasState.draw();   
      console.log("Canvas Drawn")
    }
 
  }
  
  xhttp.open("GET", "https://visual-recognition-rgre2543.c9users.io/exampleData/List_0001.csv",true);
  xhttp.send();
}

window.onload = function(){
  console.log("DOCUMENT LOAD")
  //Image elememnt
    var map = document.getElementById("ap_map");
    console.log(map);
    //Canvas Element
    var canvas = document.getElementById("ap_overlay");
    var context = canvas.getContext('2d');  
    console.log("HELLO");
    //Canvas State Variable based on the canvas element
    var cs = new CanvasState(canvas);
    
    //Image Size Variables
    var heightRatio;
    var widthRatio;
    console.log("HELLO");
    // map.onload = function()
      console.log("MAP LOAD")
      widthRatio = map.width / map.naturalWidth;
      heightRatio = map.height / map.naturalHeight;
         
      canvas.setAttribute("width", map.width);
      canvas.setAttribute("height", map.height);        
      console.log("Map Loaded");
      
      loadAPData(cs,heightRatio,widthRatio)
}