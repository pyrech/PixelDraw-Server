var xpos, ypos;

xpos = argument0;
ypos = argument1;

view_xview[0] = xpos - (view_wview[0] / 2)
view_yview[0] = ypos - (view_hview[0] / 2)

if argument2 {
  //keep view_width and height inside the room_size
  if view_wview[0] > room_width {
    view_wview[0] = room_width
    view_hview[0] = view_wview[0] / (view_wport[0] / view_hport[0]);
    view_xview[0] = 0
  }
  if view_hview[0] > room_height {
    view_hview[0] = room_height
    view_wview[0] = view_hview[0] / (view_wport[0] / view_hport[0]);
    view_yview[0] = 0
  }
  
  if view_xview[0] < 0 {
    view_xview[0] = 0
  } else {
    if view_xview[0] + view_wview[0] > room_width {
      view_xview[0] = room_width - view_wview[0]
    }
  }
  if view_yview[0] < 0 {
    view_yview[0] = 0
  } else {
    if view_yview[0] + view_hview[0] > room_height {
      view_yview[0] = room_height - view_hview[0]
    }
  }
  
}
