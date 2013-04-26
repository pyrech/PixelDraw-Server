var ww = argument0
var hh = argument1

window_set_size(display_get_width(), display_get_height())
var aspect = view_wport[0] / view_hport[0]

if hh > ww {
    view_hview[0] = hh
    view_wview[0] = hh * aspect
} else {
  view_wview[0] = ww
  view_hview[0] = ww / aspect
  
  if view_hview[0] < hh {
    view_hview[0] = hh
    view_wview[0] = ww * (ww/hh)
  }
}

