using System;
using System.IO;
using System.Text;
using System.Reflection;

namespace Holo
{
    /// <summary>
    /// Provides file, environment and minor string manipulation actions.
    /// Patched for Mono/Linux: the original used Win32 GetPrivateProfileString /
    /// WritePrivateProfileString (kernel32) which do not exist outside Windows.
    /// </summary>
    public static class IO
    {
        /// <summary>
        /// Returns the directory of the executeable (without trailing separator) as a string.
        /// </summary>
        public static string workingDirectory
        {
            get
            {
                return AppDomain.CurrentDomain.BaseDirectory.TrimEnd(Path.DirectorySeparatorChar);
            }
        }

        /// <summary>
        /// Returns the value of a key in an INI-style textfile as a string. Managed implementation.
        /// </summary>
        public static string readINI(string iniSection, string iniKey, string iniLocation)
        {
            try
            {
                string currentSection = "";
                foreach (string rawLine in File.ReadAllLines(iniLocation))
                {
                    string line = rawLine.Trim();
                    if (line.Length == 0 || line.StartsWith(";") || line.StartsWith("#"))
                        continue;

                    if (line.StartsWith("[") && line.EndsWith("]"))
                    {
                        currentSection = line.Substring(1, line.Length - 2).Trim();
                        continue;
                    }

                    int eq = line.IndexOf('=');
                    if (eq < 0)
                        continue;

                    string key = line.Substring(0, eq).Trim();
                    string val = line.Substring(eq + 1).Trim();

                    if (string.Equals(currentSection, iniSection, StringComparison.OrdinalIgnoreCase)
                        && string.Equals(key, iniKey, StringComparison.OrdinalIgnoreCase))
                    {
                        return val;
                    }
                }
            }
            catch { }
            return "";
        }

        /// <summary>
        /// Not used at runtime in this build; kept as a portable no-op.
        /// </summary>
        public static void writeINI(string iniSection, string iniKey, string iniValue, string iniLocation)
        {
        }

        /// <summary>
        /// Returns a bool, which indicates if the specified path leads to a file.
        /// </summary>
        public static bool fileExists(string fileLocation)
        {
            return File.Exists(fileLocation);
        }
    }
}
