cache_size=$(du -sk ../PlotComparisons|awk '{print $1}')
if [[ $cache_size -gt 500000 ]] ; then
ls -tr | awk '{if (NR>1)print $9}' | head -20
fi
