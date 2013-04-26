up1_key    = vk_up;
down1_key  = vk_down;
left1_key  = vk_left;
right1_key = vk_right;

up2_key    = ord('Z');
down2_key  = ord('S');
left2_key  = ord('Q');
right2_key = ord('D');


if keyboard_check(up1_key)    { up1    = true } else { up1    = false;}
if keyboard_check(down1_key)  { down1  = true } else { down1  = false;}
if keyboard_check(left1_key)  { left1  = true } else { left1  = false;}
if keyboard_check(right1_key) { right1 = true } else { right1 = false;}

//up + diag
if   up1 and ! down1 and   left1 and ! right1 { dir1 = 7;}
if   up1 and ! down1 and ! left1 and   right1 { dir1 = 9;}
if   up1 and ! down1 and ! left1 and ! right1 { dir1 = 8;}
if   up1 and ! down1 and   left1 and   right1 { dir1 = 8;}

//down + diag
if ! up1 and   down1 and   left1 and ! right1 { dir1 = 1;}
if ! up1 and   down1 and ! left1 and   right1 { dir1 = 3;}
if ! up1 and   down1 and ! left1 and ! right1 { dir1 = 2;}
if ! up1 and   down1 and   left1 and   right1 { dir1 = 2;}

//left
if ! up1 and ! down1 and ! left1 and   right1 { dir1 = 4;}
if   up1 and   down1 and ! left1 and   right1 { dir1 = 4;}

//right
if ! up1 and ! down1 and   left1 and ! right1 { dir1 = 6;}
if   up1 and   down1 and   left1 and ! right1 { dir1 = 6;}

if keyboard_check(up2_key)    { up2    = true } else { up2    = false;}
if keyboard_check(down2_key)  { down2  = true } else { down2  = false;}
if keyboard_check(left2_key)  { left2  = true } else { left2  = false;}
if keyboard_check(right2_key) { right2 = true } else { right2 = false;}

//up + diag
if   up2 and ! down2 and   left2 and ! right2 { dir2 = 7;}
if   up2 and ! down2 and ! left2 and   right2 { dir2 = 9;}
if   up2 and ! down2 and ! left2 and ! right2 { dir2 = 8;}
if   up2 and ! down2 and   left2 and   right2 { dir2 = 8;}

//down + diag
if ! up2 and   down2 and   left2 and ! right2 { dir2 = 1;}
if ! up2 and   down2 and ! left2 and   right2 { dir2 = 3;}
if ! up2 and   down2 and ! left2 and ! right2 { dir2 = 2;}
if ! up2 and   down2 and   left2 and   right2 { dir2 = 2;}

//left
if ! up2 and ! down2 and ! left2 and   right2 { dir2 = 4;}
if   up2 and   down2 and ! left2 and   right2 { dir2 = 4;}

//right
if ! up2 and ! down2 and   left2 and ! right2 { dir2 = 6;}
if   up2 and   down2 and   left2 and ! right2 { dir2 = 6;}

