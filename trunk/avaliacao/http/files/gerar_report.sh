for i in *
do
j=`echo $i | sed 's/.png/_report.png/g'`
cp "$i" "$j"
done
