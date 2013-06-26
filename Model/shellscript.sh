user_cache=$1
cache_size=$(du -sk ../PlotComparisons|awk '{print $1}')
out_dir=$4
if [[ $cache_size -gt 500000 ]] ; then
user_cache_size=$(du -sk ../PlotComparisons/$user_cache|awk '{print $1}')
if [[ $user_cache_size -gt 10000 ]] ; then
rm -rf ../PlotComparisons/$user_cache
fi
fi
export VO_CMS_SW_DIR=/afs/cern.ch/cms
source $VO_CMS_SW_DIR/cmsset_default.sh
cd ../CMGTools/CMSSW_5_3_9/src
eval `scramv1 runtime -sh`
mkdir -p ../../../PlotComparisons/$user_cache
cd ../../../PlotComparisons/$user_cache
python $CMSSW_BASE/src/CMGTools/RootTools/python/utils/fileComparator.py $2 $3 -o $out_dir -w -1 histogram_1 -2 histogram_2 -b
echo "<?php include( '../../../View/header/header.php' ); echo '<h4>histogram_1: "$5"</h4>'; echo '<h4>histogram_2: "$6"</h4>'; echo '<div id=\'imageContainer\'>'; include( './index.html' ); echo '</div>'; include( './delete_from_cache.html' ); include( '../../../View/footer/footer.php' );" > $out_dir/comparison_results.php
echo "<form name='delete_from_user_cache' action='../../../Model/delete_from_user_cache.php' action='GET'> <input type='hidden' name='dir_name' value='"$out_dir"'><div id='compareButtonSection'><input type='submit' value='Delete from cache'></div></form>" > $out_dir/delete_from_cache.html
