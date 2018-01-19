-- fill in trip_headsign

UPDATE trips
SET trip_headsign=(
	SELECT stop_name FROM (
		SELECT s.stop_name, st.trip_id, st.stop_id, MAX(stop_sequence)
		FROM stop_times st
		INNER JOIN stops s ON st.stop_id=s.stop_id
		WHERE st.trip_id=trips.trip_id
	)
)
WHERE trip_headsign='';