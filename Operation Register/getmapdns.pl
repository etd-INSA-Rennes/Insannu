#!/usr/bin/perl
for($i=8 ; $i<15 ; $i++) {
	for($j=0 ; $j<255 ; $j++) {
		$result = `nslookup 10.133.$i.$j sam.insa-rennes.fr`;
		if($result =~ /pc-r(\w{2,3}\-?\d{3})1\.res\.insa/) {
			print "10.133.$i.$j:$1\n";
		}
	}
}
