out_dir=$3
export VO_CMS_SW_DIR=/afs/cern.ch/cms
source $VO_CMS_SW_DIR/cmsset_default.sh
cd ../CMGTools/CMSSW_5_3_9/src
eval `scramv1 runtime -sh`
cd ../../../PlotComparisons
python $CMSSW_BASE/src/CMGTools/RootTools/python/utils/fileComparator.py $1 $2 -o $out_dir -w -1 histogram_1 -2 histogram_2 -b
echo "<?php include( '../../View/header/header.php' ); echo '<h4>histogram_1: "$4"</h4>'; echo '<h4>histogram_2: "$5"</h4>'; echo '<div id=\'imageContainer\'>'; include( './index.html' 
); echo '</div>'; include( './delete_from_cache.html' ); include( '../../View/footer/footer.php' );" > $out_dir/comparison_results.php
echo "<form name='delete_from_user_cache' action='../../Model/delete_from_user_cache.php' action='GET'> <input type='hidden' name='dir_name' value='"$out_dir"'><div id='compareButtonSection'><input type='submit' value='Delete from cache'></div></form>" > $out_dir/delete_from_cache.html
