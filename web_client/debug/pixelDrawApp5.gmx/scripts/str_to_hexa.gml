var _result,_c;
argument0 = string_upper(argument0)
_result = 0
for (_c = string_length(argument0) _c > 0 _c -= 1)
_result = _result << 4 | (string_pos(string_char_at(argument0, _c), "0123456789ABCDEF") - 1)
return _result
