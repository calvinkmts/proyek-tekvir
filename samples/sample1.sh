#!/bin/bash

out=/tmp/std.out

echo "hello" > $out

/bin/date '+%a, %e %b %Y %H:%M:%S %Z' > $out

#/usr/bin/curl -x https://www.bankmandiri.co.id/kurs 2>/dev/null | /bin/sed 's/ \(<\/\?div\)/\n\1/g' | /bin/sed -n '/box-currency/,+2{/>USD</{n;h;n;H;n;H;n;H;g;s/^.*span [^>]*> \(.*\)\([0-9]\{3\}\)\.\([0-9]\).*span [^>]*> \(.*\)\([0-9]\{3\}\)\.\([0-9]\).*/Mandiri\tBeli:\1.\2,\30 Jual:\4.\5,\60/g;p}}' >> $out

#/usr/bin/curl -x https://www.bni.co.id/id-id/beranda/informasivalas 2>/dev/null | /bin/sed -n '/<tbody>/{p}' | /bin/sed -n '1{s/.*USD[^0-9]*\([0-9,\.]*\)[^0-9]*\([0-9,\.]*\).*/BNI\tBeli:\2 Jual:\1/g;p}' >> $out

#/usr/bin/curl -x https://www.bca.co.id 2>/dev/null | /bin/sed -n '/tbody/,/\/tbody/{/USD/{n;h;n;H;g;s/^[^0-9]*\([0-9,\.]*\)[^0-9]*\([0-9,\.]*\).*/BCA\tBeli:\2 Jual:\1/g;p}}' >> $out

echo '==========' >> $out