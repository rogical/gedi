#!/usr/bin/perl

$MAX_SIZE=1000000;
#$rawfile="/tmp/kangry_rawfile";

for ($n=0; $n<(@ARGV+0);$n++)
  {
  $name=$ARGV[$n];

  if (-f $name)
   {
      $rawfile="/tmp/$name";

      open(IN, $name);
      open (OUT,">$rawfile");

      ##read FILEHANDLE, $VAR, LENGTH [ , OFFSET ]
      $size=read IN,$RAW,$MAX_SIZE;
      print "uploading $name ($size bytes)\n";

      ## URLENCODE from http://support.internetconnection.net/CODE_LIBRARY/Perl_URL_Encode_and_Decode.shtml
      $RAW =~ s/([^A-Za-z0-9])/sprintf("%%%02X", ord($1))/seg;

      print OUT "name=$name&size=$size&type=binary/octet-stream&host=$host&file=";
      print OUT $RAW;

     close OUT;
     close IN;
   }; # file exists

  };
