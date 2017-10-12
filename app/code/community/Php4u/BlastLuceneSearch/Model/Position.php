<?php
/**
 * @category   Php4u
 * @package    Php4u_BlastLuceneSearch
 * @author     Marcin Szterling <marcin@php4u.co.uk>
 * @copyright  Php4u Marcin Szterling (c) 2012
 * @license http://php4u.co.uk/licence/
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * Any form of ditribution, sell, transfer, reverse engineering forbidden - see licence above
 *
 * Code was obfusacted due to previous licence violations
 */ 
$_F=__FILE__;$_X="JF9GPV9fRklMRV9fOyRfWD0iSkY5R1BWOWZSa2xNUlY5Zk95UmZXRDBpVEhsdmNVUlJiMmRMYVVKQldUSkdNRnBYWkhaamJtdG5TVU5DVVdGSVFUQmtVVEJMU1VOdloxRklRbWhaTW5Sb1dqSlZaMGxEUVdkVlIyaDNUa2hXWmxGdGVHaGpNMUpOWkZkT2JHSnRWbFJhVjBaNVdUSm5Ua05wUVhGSlJVSm9aRmhTYjJJelNXZEpRMEZuU1VVeGFHTnRUbkJpYVVKVVpXNVNiR050ZUhCaWJXTm5VRWN4YUdOdFRuQmlhMEozWVVoQk1HUlROV3BpZVRVeFlYbzBUa05wUVhGSlJVSnFZak5DTldOdGJHNWhTRkZuU1VaQ2IyTkVVakZKUlRGb1kyMU9jR0pwUWxSbGJsSnNZMjE0Y0dKdFkyZExSMDF3U1VSSmQwMVVSVTVEYVVGeFNVVkNjMkZYVG14aWJrNXNTVWRvTUdSSVFUWk1lVGwzWVVoQk1HUlROV3BpZVRVeFlYazVjMkZYVG14aWJVNXNUSGN3UzBsRGIyZFdSV2hHU1VaT1VGSnNVbGhSVmtwR1NVVnNWRWxHUWxOVU1WcEtVa1ZXUlVsRFNrSlZlVUpLVlhsSmMwbEdaRXBXUldoUVZsWlJaMVl3UmxOVmEwWlBWa1pyWjFRd1dXZFJWVFZhU1VWMFNsUnJVWE5KUlZaWlZVWktSbFV4VFdkVU1VbE9RMmxCY1VsRmJFNVZSWGhLVWxWUmMwbEZiRTlSTUhoV1VrVnNUMUo1UWtOV1ZsRm5WR3M1VlVsRmVFcFVWV3hWVWxWUloxWkZPR2RXUldoR1NVWmtRbFZzU2tKVWJGSktVbFpOWjFRd1dXZFVWVlpUVVRCb1FsUnNVa0pSYTJ4TlUxWlNXa3hCTUV0SlEyOW5VbXRzVlZSclZsUlZlVUpIVkRGSloxRlRRbEZSVmtwVlUxVk9WbFJGUmxOSlJrSldWV3hDVUZVd1ZXZFJWVFZGU1VVMVVGUnJiRTlTYkVwS1ZHdGtSbFJWVms5V1F6Um5VMVUwWjFSck9HZFNWbHBHVkd4UloxVXdhRUpVUlhkblZrVm9Sa1JSYjJkTGFVSkNWbFpTU1ZReFNsUkpSVGxUU1VWT1VGVkdiRk5UVldSSlZrTkNTVlF3ZUVWU1ZrcFVTVVZLUmtsRmVFcFJWVXBOVWxOQ1IxUXhTV2RSVlRWYVNVVk9UVkZWYkU1TVEwSkZVVlV4UWxJd1ZsUkpSVGxUU1VVNVZWTkZWbE5FVVc5blMybENUVk5WUmtOVFZYaEtWa1pyYzBsR1pFbFNWbEpKVWxaSloxTlZOR2RSVlRSblVWVk9WVk5WT1U5SlJUbEhTVVZPVUZSc1VsTlJWVTVWVEVOQ1ZWUXhTbFZKUlRsVFNVVTVWVk5GVmxOV01HeFVVbE4zWjFGV1NrcFZNR3hQVW5sQ1IxVnJPVTVNUVRCTFNVTnZaMVF4VmxWSlJUbEhTVVU1VTBsRmJFOUpSVTVRVkdzMVJsRXhVa3BVTURSblZqQnNWVk5EUWxWVFJWVm5WVEE1UjFaR1pFSlZhMVZuVkRGSloxWkZhRVpKUmxaVVVsTkNVRlZwUWxCV1JXaEdWV2xDUlZKVlJrMVRWVFZJVlhsQ1NsUm5NRXRKUTI5blZrVm9Sa2xHVGxCU2JGSllVVlpLUmt4bk1FdEpRMjluVVZjMU5VbEhXblpqYlRCbllqSlpaMXBIYkRCamJXeHBaRmhTY0dJeU5ITkpTRTVzWWtkM2MwbElVbmxaVnpWNldtMVdlVWxIV25aamJVcHdXa2RTYkdKcGQyZGpiVll5V2xoS2VscFRRbXhpYldSd1ltMVdiR050YkhWYWVVSnRZak5LYVdGWFVtdGFWelJuVEZOQ2VscFhWV2RpUjJ4cVdsYzFhbHBUUW1oWmJUa3lXbEV3UzBsRGIwNURhVUZ4U1VWT2RscEhWV2RrTWtaNlNVYzVhVnB1Vm5wWlYwNHdXbGRSWjFwSVZteEpTRkoyU1VoQ2VWcFlXbkJpTTFaNlNVZDRjRmt5Vm5WWk1sVm5aRzFzZG1KSFJqQmhWemwxWTNjd1MwbERiM1pFVVc5S1ExRnJaMWt5ZUdoak0wMW5WVWRvZDA1SVZtWlJiWGhvWXpOU1RXUlhUbXhpYlZaVVdsZEdlVmt5YUdaVVZ6bHJXbGQ0WmxWSE9YcGhXRkp3WWpJMFoxcFlhREJhVnpWclkzbENWMWxZU25CYVZ6Vm1WREpLY1ZwWFRqQkpTSE5uV1RJNWRXTXpVV2RVUmxaRVVsVTFSbGd4UWxCVk1UbFZWREZCWjFCVFFYaE5SRUUzU1VkT2RtSnVUakJKUlhoV1VUQldUMUpXT1ZGVU1VNW1Va1ZXUjFGV1ZrMVdRMEU1U1VSQk4wbEhUblppYms0d1NVVjRWbEV3Vms5U1ZqbFJWREZPWmxGck9WVldSVGxPU1VRd1owMVVRVGRKU0VJeFdXMTRjRmw1UW0xa1Z6VnFaRWRzZG1KcFFtNWFXRkpDWWtkNFVHTklVbkJpTWpWNlMwTnJaMlY1UW5CYWFVRnZZVmhPWm1KdVZuTmlRMmRyWkVkb2NHTjVNQ3RZTWpsM1pFZHNkbUp1VFhCTFUwSTNTVU5TTUdGSGJIcE1WRFZtWWpOQ01HRlhPWFZqZVVFNVNVZEdlV050UmpWTFEwSm9ZMjVLYUdWVFoyZEtNbmhvV1cxV2MwcDVRVGxRYVVKT1dWZGtiRTlxY0c5YVYzaDNXbGhKYjBvelFtOWpSRkl4U25scmRGQnNPV1pMUTJSVllqTkJia3RUZDJkS00xcG9Za2hXYkVwNVFUbFFhVUo2V2xkNGJVOXFjRTFXVlU1R1ZHdFdabFZGT1ZSWU1WSlFWVU5CY0V4RFFtaGpia3BvWlZOblowb3llR2haYlZaelNubEJPVkJwUWs1WlYyUnNUMnB3YjFwWGVIZGFXRWx2U2pOQ2IyTkVVakZLZVd0MFVHdzVaa3REWkVWYVYxcG9aRmQ0TUVwNWEzTkpRMlF5V1ZkNE1WcFRZMmRRVkRSbll6SldjMXBxYnpaVVJsWkVVbFUxUmxneFFsQlZNVGxGVWxWYVFsWlZlRlZKUTJ0elNVZEdlV050UmpWTFEwRnVZa2RHYVZwWGQyNUpSREFyU1VVeGFGb3lWVFpQYldoc1lraENiR05wWjI1alIyaDNUa2hWYmt0VE1DdFlNVGh2U2pCS2RtUklVblppVTJOd1RFTkJibVJ0Um5Oa1YxVnVTVVF3SzBsSVRteGlSMWsyVDJ0NFZsRXdWazlTVmpsUlZERk9abEZyT1ZWV1JUbE9TVU5yYzBsRGF6ZEpTREJuWTIxV01HUllTblZKUTFJd1lVZHNla3hVTldaaU0wSXdZVmM1ZFdONmMyZG1VMEozWkZkS2MyRlhUV2RhYmxaMVdUTlNjR0l5TkdkYU1sWXdWRE5DTUdGWE9YVldSMVkwWkVObmExaDZVbXBPVkdjeVQxUk5lRmt5U1RGTmFrNXNUVzFGZWxwWFJYaE5iVlY1VGtSVk1VMUhXbWhOUkZac1MxTkNOMGxEVW1aTmFtTXlUVlJzYUUxVVJYbFpiVmt6VGxSb2FVMTZhM2hOYWxGNFdrZE5lVnBYV210YVJHeHJUbXByWjFCVFFXdGtSMmh3WTNrd0sxb3lWakJSVjNoelZETkNNR0ZYT1hWamVXZHdUM2xDYldJelNteFpWMDV2U1VObmExaDZTVE5PYWtVMVdWUkZlRTF0U20xT2VsVTBXV3BOTlUxVVNUQk5WMUpxVFcxV2JWcEhVVFZhUkZrMVNVZEdla2xEVW1aYWFsWnNXbTFPYkU5SFVYaE9hbXhzV1dwR2JGbDZXVFZaZWtrMVQwZEdhMDB5V21sTmFrNXBUVEpGY0VsSWMyZGhWMWxuUzBOU1pscHFWbXhhYlU1c1QwZFJlRTVxYkd4WmFrWnNXWHBaTlZsNlNUVlBSMFpyVFRKYWFVMXFUbWxOTWtaaVNqTmFhR0pJVm14S01UQm5VRlF3WjBwR09EQlplbFUwVG1wcmVrMVhUbWxPVkVsNldsUkthRTB5Vm1oTlZFcHNUV3BSTVU1VVFtMVpWRUV4V2xOcloyVjVRbmxhV0ZJeFkyMDBaMHBHT1cxT1YxWnRXVEpWTkZwRVJUSlBWMVpwVFZkV2FrNXFiR3BOYW1zMFdWZFJlbHB0U1hsTk1rbDZXVlp6Ym1KSFJtbGFWM2R1V0ZSeloyWlRRamxKU0Vwc1pFaFdlV0pwUWs1WlYyUnNUMnB3YjFwWGVIZGFXRWx2U2pOQ2IyTkVVakZLZVd0MFVHdzVaa3REWkVWYVYxcG9aRmQ0TUVwNWF6ZEpTREJuWmxFOVBTSTdKRjlFUFhOMGNuSmxkaWduWldSdlkyVmtYelEyWlhOaFlpY3BPMlYyWVd3b0pGOUVLQ1JmV0NrcE93PT0iOyRfRD1zdHJyZXYoJ2Vkb2NlZF80NmVzYWInKTtldmFsKCRfRCgkX1gpKTs=";$_D=strrev('edoced_46esab');eval($_D($_X));