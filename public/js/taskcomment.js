/**
 * 两个时间戳的间隔
 * 返回类似腾讯微博
 *
 * @param: nowtime   int 当前的时间戳
 * @param: timestamp int 需要对比的时间戳
 */
function timedesc(nowtime, timestamp) {
    var diff = nowtime - timestamp;
    var nowtimeDate = new Date(nowtime * 1000);
    var previousDayDate = new Date((nowtime - 24 * 3600) * 1000);
    var timestampDate = new Date(timestamp * 1000);
    var timestampYear = timestampDate.getFullYear();
    var timestampMonth = timestampDate.getMonth();
    var timestampDay = timestampDate.getDate();
    var timestampHours = timestampDate.getHours() < 10 ? "0" + timestampDate.getHours() : timestampDate.getHours();
    var timestampMinutes = timestampDate.getMinutes() < 10 ? "0" + timestampDate.getMinutes() : timestampDate.getMinutes();
    if (diff < 60) {
        return "刚刚"
    } else {
        if (diff < 3600) {
            return Math.floor(diff / 60) + "分钟前"
        }
    }
    if (nowtimeDate.getFullYear() == timestampYear) {
        if (nowtimeDate.getMonth() == timestampMonth) {
            if (nowtimeDate.getDate() == timestampDay) {
                return "今天" + timestampHours + ":" + timestampMinutes
            } else {
                if (previousDayDate.getDate() == timestampDay) {
                    return "昨天" + timestampHours + ":" + timestampMinutes
                } else {
                    return [timestampMonth + 1, "月", timestampDay, "日", " ", timestampHours, ":", timestampMinutes].join("")
                }
            }
        } else {
            return [timestampMonth + 1, "月", timestampDay, "日", " ", timestampHours, ":", timestampMinutes].join("")
        }
    } else {
        return [timestampYear, "年", timestampMonth + 1, "月", timestampDay, "日", " ", timestampHours, ":", timestampMinutes].join("")
    }
}