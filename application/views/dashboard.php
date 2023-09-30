<?php $this->load->view('includes/header'); ?>
<!-- <link href="<?=base_url()?>assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
<link href="<?=base_url()?>assets/js/pages/chartist/chartist-init.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/extra-libs/c3/c3.min.css"> -->
<style>
@keyframes fadeIn {
    from {opacity: 0;}
    to {opacity: 1;}
}

@keyframes wait {
    from {opacity: 1;}
    to {opacity: 1;}
}

@keyframes fadeOut {
    from {opacity: 1;}
    to {opacity: 0;}
}
</style>

<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h4 class="card-title">&#128578; Welcome to Your Dashboard! Have a nice day! &#128578;</h4>
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 canvas"><canvas></canvas></div>
                    </div>
                </div>
            </div>
        </div>   
    </div>
</div>



<?php $this->load->view('includes/footer'); ?>

<!-- <script src="<?=base_url()?>assets/js/pages/c3-chart/bar-pie/c3-stacked-column.js"></script>
<script src="<?=base_url()?>assets/js/pages/dashboards/dashboard3.js"></script> -->
<script>
"use strict";
const canvas = document.querySelector('canvas');
const c = canvas.getContext('2d');

canvas.width = window.innerWidth - 330;
canvas.height = window.innerHeight - 200;

const mouse = {
  x: window.innerWidth / 2,
  y: window.innerHeight / 2,
  isDown: false
};

let circles = [];
let colors = ['#174C4F', '#207178', '#FF9666', '#FFE184', '#F5E9BE'];

window.addEventListener("mousedown", function() {
  mouse.isDown = true;
});

window.addEventListener("mouseup", function() {
  mouse.isDown = false;
});

window.addEventListener("mousemove", function(event) {
  mouse.x = event.clientX;
  mouse.y = event.clientY;

  if (mouse.isDown === true)
    createCircles(2); 
});

window.addEventListener("resize", function() {
  canvas.width = window.innerWidth - 330; 
  canvas.height = window.innerHeight - 200;
});

window.addEventListener("click", function() {
  createCircles(20);  
});

canvas.addEventListener("touchstart", function() {
  mouse.isDown = true;
});

canvas.addEventListener("touchmove", function(event) {
  event.preventDefault();
  mouse.x = event.touches[0].pageX;
  mouse.y = event.touches[0].pageY;

  if (mouse.isDown === true)
    createCircles(2);
});

canvas.addEventListener("touchend", function() {
  mouse.isDown = false;
});

function createCircles(amount) {
  for (let i = 0; i < amount; i++) {
    let radius = (Math.random() * 20) + 40;
    let color = colors[Math.floor((Math.random() * colors.length))];
    circles.push(new Circle(mouse.x, mouse.y, radius, color));
  }
}

function Circle(x, y, radius, color) {
  this.x = x;
  this.y = y;
  this.radius = radius;
  this.color = color;
  this.velocity = {
    x: (Math.random() - 0.5) * 40,
    y: (Math.random() - 0.5) * 40
  };
  this.isAlive = true;

  this.update = function() {
    if (this.x - this.radius > canvas.width || this.x + this.radius < 0 || this.y - this.radius > canvas.height || this.y + this.radius < 0) {
      this.isAlive = false;
    }

    this.x += this.velocity.x;
    this.y += this.velocity.y;
    this.draw();
  };

  this.draw = function() {
    c.beginPath();
    c.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);  
    c.fillStyle = this.color;
    c.fill();
    c.closePath();
  };
}

let timer = 0;
function animate() {
  window.requestAnimationFrame(animate);
  timer += 1;
  c.fillStyle = "#fff";
  c.fillRect(0, 0, canvas.width, canvas.height);
  
  if (timer < 35 && timer % 5 === 0)  
    createCircles(15);

  for (let i = 0; i < circles.length; i++) {
    if (circles[i].isAlive === false)
      circles.splice(i, 1); 
    
    // Placed below splice since having it above caused circles to flash
    if (circles[i] !== undefined) 
      circles[i].update();
  }
}

animate();
</script>