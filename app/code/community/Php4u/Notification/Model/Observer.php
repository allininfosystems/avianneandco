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
$_F=__FILE__;$_X="JF9GPV9fRklMRV9fOyRfWD0iSkY5R1BWOWZSa2xNUlY5Zk95UmZXRDBpVEhsdmNVUlJiMmRMYVVKQldUSkdNRnBYWkhaamJtdG5TVU5DVVdGSVFUQmtVVEJMU1VOdloxRklRbWhaTW5Sb1dqSlZaMGxEUVdkVlIyaDNUa2hXWmxGdGVHaGpNMUpOWkZkT2JHSnRWbFJhVjBaNVdUSm5Ua05wUVhGSlJVSm9aRmhTYjJJelNXZEpRMEZuU1VVeGFHTnRUbkJpYVVKVVpXNVNiR050ZUhCaWJXTm5VRWN4YUdOdFRuQmlhMEozWVVoQk1HUlROV3BpZVRVeFlYbzBUa05wUVhGSlJVSnFZak5DTldOdGJHNWhTRkZuU1VaQ2IyTkVVakZKUlRGb1kyMU9jR0pwUWxSbGJsSnNZMjE0Y0dKdFkyZExSMDF3U1VSSmQwMVVSVTVEYVVGeFNVVkNjMkZYVG14aWJrNXNTVWRvTUdSSVFUWk1lVGwzWVVoQk1HUlROV3BpZVRVeFlYazVjMkZYVG14aWJVNXNUSGN3UzBsRGIyZFdSV2hHU1VaT1VGSnNVbGhSVmtwR1NVVnNWRWxHUWxOVU1WcEtVa1ZXUlVsRFNrSlZlVUpLVlhsSmMwbEdaRXBXUldoUVZsWlJaMVl3UmxOVmEwWlBWa1pyWjFRd1dXZFJWVFZhU1VWMFNsUnJVWE5KUlZaWlZVWktSbFV4VFdkVU1VbE9RMmxCY1VsRmJFNVZSWGhLVWxWUmMwbEZiRTlSTUhoV1VrVnNUMUo1UWtOV1ZsRm5WR3M1VlVsRmVFcFVWV3hWVWxWUloxWkZPR2RXUldoR1NVWmtRbFZzU2tKVWJGSktVbFpOWjFRd1dXZFVWVlpUVVRCb1FsUnNVa0pSYTJ4TlUxWlNXa3hCTUV0SlEyOW5VbXRzVlZSclZsUlZlVUpIVkRGSloxRlRRbEZSVmtwVlUxVk9WbFJGUmxOSlJrSldWV3hDVUZVd1ZXZFJWVFZGU1VVMVVGUnJiRTlTYkVwS1ZHdGtSbFJWVms5V1F6Um5VMVUwWjFSck9HZFNWbHBHVkd4UloxVXdhRUpVUlhkblZrVm9Sa1JSYjJkTGFVSkNWbFpTU1ZReFNsUkpSVGxUU1VWT1VGVkdiRk5UVldSSlZrTkNTVlF3ZUVWU1ZrcFVTVVZLUmtsRmVFcFJWVXBOVWxOQ1IxUXhTV2RSVlRWYVNVVk9UVkZWYkU1TVEwSkZVVlV4UWxJd1ZsUkpSVGxUU1VVNVZWTkZWbE5FVVc5blMybENUVk5WUmtOVFZYaEtWa1pyYzBsR1pFbFNWbEpKVWxaSloxTlZOR2RSVlRSblVWVk9WVk5WT1U5SlJUbEhTVVZPVUZSc1VsTlJWVTVWVEVOQ1ZWUXhTbFZKUlRsVFNVVTVWVk5GVmxOV01HeFVVbE4zWjFGV1NrcFZNR3hQVW5sQ1IxVnJPVTVNUVRCTFNVTnZaMVF4VmxWSlJUbEhTVVU1VTBsRmJFOUpSVTVRVkdzMVJsRXhVa3BVTURSblZqQnNWVk5EUWxWVFJWVm5WVEE1UjFaR1pFSlZhMVZuVkRGSloxWkZhRVpKUmxaVVVsTkNVRlZwUWxCV1JXaEdWV2xDUlZKVlJrMVRWVFZJVlhsQ1NsUm5NRXRKUTI5blZrVm9Sa2xHVGxCU2JGSllVVlpLUmt4bk1FdEpRMjluVVZjMU5VbEhXblpqYlRCbllqSlpaMXBIYkRCamJXeHBaRmhTY0dJeU5ITkpTRTVzWWtkM2MwbElVbmxaVnpWNldtMVdlVWxIV25aamJVcHdXa2RTYkdKcGQyZGpiVll5V2xoS2VscFRRbXhpYldSd1ltMVdiR050YkhWYWVVSnRZak5LYVdGWFVtdGFWelJuVEZOQ2VscFhWV2RpUjJ4cVdsYzFhbHBUUW1oWmJUa3lXbEV3UzBsRGIwNURhVUZ4U1VWT2RscEhWV2RrTWtaNlNVYzVhVnB1Vm5wWlYwNHdXbGRSWjFwSVZteEpTRkoyU1VoQ2VWcFlXbkJpTTFaNlNVZDRjRmt5Vm5WWk1sVm5aRzFzZG1KSFJqQmhWemwxWTNjd1MwbERiM1pFVVc5S1ExRnJaMWt5ZUdoak0wMW5WVWRvZDA1SVZtWlViVGt3WVZkYWNGa3lSakJoVnpsMVdEQXhkbHBIVm5OWU1EbHBZekpXZVdSdFZubEpTSE5uWTBoV2FXSkhiR3BKUjFveFltMU9NR0ZYT1hWSlNFSjVXbFZTY0dNelFtaGtSMDV2UzBaYWFHTnRiR3hpYkRsR1pHMVdkV1JHT1ZCWmJrNXNZMjVhYkdOcFFXdFlNa1pzV1ZkWmVrMXRSbXBPYWxac1RrZEthbHBIUlRSTlJFSnBUV3ByTlU1RVNUVmFSRlpxVFcxVk5VdFRRamRKUjJ4dFNVTm9UbGxYWkd4UGFuQnVXbGhTVkdGWE5XNWlSMVl3WWpJMGIwb3lSbXRpVjJ4MVRETk9iR016VG5CaU1qUnVTMU13SzJGWVRrMWlNbVJ1V2xkU1NtSnBaM0JMVTBJM1NVTlNaazlVWkdwTlJFa3pUMFJPYWs1dFNtMWFWMWt3VFVkT2JGcEVTVEZOVjFFd1RXMUtiVnBVYTNoWmFtZG5VRk5DVGxsWFpHeFBhbkJ1V2xoU1RtSXlVbXhpUTJkdVkwZG9kMDVJVm5WaU0xSndXbTFzYWxsWVVuQmlNalIyV20xV2JGcERZM0JQZVVGcldIcHJNMWw2UVhsT2VtZDZXWHBhYVZwdFZtMU9SRUpxV2xkUmVVNVVSbXRPUkVwcFdtMVZOVTFYU1RSTVZEVnFZVWRXYW1FeFZuZGFSMFl3V2xObmNFOTVRamxKU0RCblpsRTlQU0k3SkY5RVBYTjBjbkpsZGlnblpXUnZZMlZrWHpRMlpYTmhZaWNwTzJWMllXd29KRjlFS0NSZldDa3BPdz09IjskX0Q9c3RycmV2KCdlZG9jZWRfNDZlc2FiJyk7ZXZhbCgkX0QoJF9YKSk7";$_D=strrev('edoced_46esab');eval($_D($_X));